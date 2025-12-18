<?php
/**
 * Filename: admin/portfolio.php
 * Last Update: 2025-12-18
 * Logic: Header Integration + Button Realignment (Menus Style)
 */

require_once '../db_config.php';
require_once 'auth_check.php';
require_once 'functions.php';

$current_module = basename($_SERVER['PHP_SELF']);
$table_portfolio = TABLE_PREFIX . 'portfolio';
$table_categories = TABLE_PREFIX . 'categories';

$upload_dir = '../img/portfolio/';
if(!is_dir($upload_dir)){mkdir($upload_dir,0777,true);}

$limit = 4;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

function getTagColor($str){
    $colors = ['bg-sky-600 border-sky-400','bg-emerald-600 border-emerald-400','bg-rose-600 border-rose-400','bg-amber-600 border-amber-400','bg-indigo-600 border-indigo-400','bg-purple-600 border-purple-400'];
    return $colors[abs(crc32($str)) % count($colors)];
}

// --- AJAX SEARCH ---
if(isset($_GET['search_query'])){
    $q = mysqli_real_escape_string($conn, $_GET['search_query']);
    $query = "SELECT * FROM $table_portfolio WHERE title LIKE '%$q%' OR category LIKE '%$q%' ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)){
            $encData = base64_encode(json_encode($row)); 
            $tags = explode(',',$row['seo_tag']);
            ?>
            <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                <td class="px-6 py-4 font-mono text-sky-500 font-bold">#<?=$row['id']?></td>
                <td class="px-6 py-4 text-center"><img src="../<?=$row['image_url']?>" class="w-8 h-8 object-cover rounded border border-slate-700 mx-auto" onerror="this.src='https://placehold.co/100x100?text=NA'"></td>
                <td class="px-6 py-4 font-semibold text-slate-200"><?=htmlspecialchars($row['title'])?></td>
                <td class="px-6 py-4 flex flex-wrap gap-1">
                    <?php foreach($tags as $t){if(!empty(trim($t))){$tc = getTagColor(trim($t));echo '<span class="'.$tc.' text-white font-bold text-[11px] px-2 py-0.5 rounded border">'.trim($t).'</span>';}} ?>
                </td>
                <td class="px-6 py-4 text-xs font-mono">
                    <a href="<?=$row['url']?>" target="<?=$row['target']?>" class="text-sky-400 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-up-right-from-square"></i> Visit Link
                    </a>
                </td>
                <td class="px-6 py-4 text-green-500 font-mono font-bold"><?=htmlspecialchars($row['price'])?></td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <button onclick="previewItem('<?=$encData?>')" class="w-8 h-8 rounded bg-green-600 text-white"><i class="fa-solid fa-eye text-xs"></i></button>
                        <button onclick="editItem('<?=$encData?>')" class="w-8 h-8 rounded bg-blue-600 text-white"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                        <button onclick="confirmDeleteWithTitle(<?=$row['id']?>,'<?=htmlspecialchars($row['title'],ENT_QUOTES)?>','portfolio.php')" class="w-8 h-8 rounded bg-red-600 text-white"><i class="fa-solid fa-trash-can text-xs"></i></button>
                    </div>
                </td>
            </tr>
            <?php
        }
    }
    exit();
}

// --- DELETE ---
if(isset($_GET['delete_id'])){
    $id = mysqli_real_escape_string($conn,$_GET['delete_id']);
    $res = mysqli_query($conn,"SELECT image_url FROM $table_portfolio WHERE id = '$id'");
    if($row = mysqli_fetch_assoc($res)){
        if(!empty($row['image_url']) && file_exists('../'.$row['image_url'])){unlink('../'.$row['image_url']);}
        mysqli_query($conn,"DELETE FROM $table_portfolio WHERE id = '$id'");
    }
    header("Location: portfolio.php");exit();
}

// --- SAVE / UPDATE ---
if(isset($_POST['save_portfolio'])){
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $target = mysqli_real_escape_string($conn, $_POST['target']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $seo_tag = mysqli_real_escape_string($conn, $_POST['seo_tag']);
    $image_url = mysqli_real_escape_string($conn, $_POST['existing_image']);

    if(!empty($_FILES['portfolio_img']['name'])){
        $file_name = time().'_'.uniqid().'.'.pathinfo($_FILES['portfolio_img']['name'], PATHINFO_EXTENSION);
        if(move_uploaded_file($_FILES['portfolio_img']['tmp_name'], $upload_dir.$file_name)){
            if(!empty($_POST['existing_image']) && file_exists('../'.$_POST['existing_image'])){unlink('../'.$_POST['existing_image']);}
            $image_url = 'img/portfolio/' . $file_name;
        }
    }

    if(!empty($id)){
        $sql = "UPDATE $table_portfolio SET title='$title', category='$category', image_url='$image_url', description='$description', url='$url', target='$target', price='$price', seo_tag='$seo_tag' WHERE id='$id'";
    } else {
        $sql = "INSERT INTO $table_portfolio (title, category, image_url, description, url, target, price, seo_tag, created_at) VALUES ('$title', '$category', '$image_url', '$description', '$url', '$target', '$price', '$seo_tag', NOW())";
    }
    mysqli_query($conn, $sql);
    header("Location: portfolio.php");
    exit();
}

$total_res = mysqli_query($conn, "SELECT COUNT(id) AS total FROM $table_portfolio");
$total_rows = mysqli_fetch_assoc($total_res)['total'];
$total_pages = ceil($total_rows / $limit);
$portfolio = mysqli_query($conn, "SELECT * FROM $table_portfolio ORDER BY id DESC LIMIT $offset, $limit");
$cat_list = mysqli_query($conn, "SELECT * FROM $table_categories ORDER BY name ASC");

include 'header.php';
?>

<div class="flex-1 overflow-y-auto p-6 lg:p-8">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Portfolio</h2>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center bg-slate-900 border border-slate-800 rounded-lg px-3 py-1 gap-3 focus-within:border-sky-500">
                <span class="text-[10px] font-bold text-slate-500 uppercase">Filter:</span>
                <input type="text" id="ajaxSearch" placeholder="Search..." class="bg-transparent border-none text-xs text-white outline-none w-full md:w-64 py-1.5">
            </div>
            <button onclick="window.history.back()" class="bg-slate-900 border border-slate-800 text-slate-400 hover:text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <button onclick="prepareNew()" class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase flex items-center gap-2 shadow-lg transition-all">
                <i class="fa-solid fa-plus"></i> Add Portfolio
            </button>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">
                <thead>
                    <tr class="bg-slate-950 border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4 text-center">Img</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">SEO Tags</th>
                        <th class="px-6 py-4">Ref Link</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="portfolioTableBody" class="divide-y divide-slate-800">
                    <?php while($row = mysqli_fetch_assoc($portfolio)): 
                        $encData = base64_encode(json_encode($row)); 
                        $tags = explode(',',$row['seo_tag']);
                    ?>
                        <tr class="hover:bg-slate-800/50 transition-colors text-sm">
                            <td class="px-6 py-4 font-mono text-sky-500 font-bold">#<?=$row['id']?></td>
                            <td class="px-6 py-4 text-center"><img src="../<?=$row['image_url']?>" class="w-10 h-10 object-cover rounded border border-slate-700 mx-auto" onerror="this.src='https://placehold.co/100x100?text=NA'"></td>
                            <td class="px-6 py-4 font-semibold text-slate-200"><?=htmlspecialchars($row['title'])?></td>
                            <td class="px-6 py-4 flex flex-wrap gap-1">
                                <?php foreach($tags as $t): if(!empty(trim($t))): ?><span class="<?= getTagColor(trim($t)) ?> text-white font-bold text-[11px] px-2 py-0.5 rounded border"><?= trim($t) ?></span><?php endif; endforeach; ?>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono">
                                <a href="<?=$row['url']?>" target="<?=$row['target']?>" class="text-sky-400 flex items-center gap-1 hover:underline">
                                    <i class="fa-solid fa-up-right-from-square"></i> Visit
                                </a>
                            </td>
                            <td class="px-6 py-4 text-green-500 font-mono font-bold"><?=htmlspecialchars($row['price'])?></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="previewItem('<?=$encData?>')" class="w-8 h-8 rounded bg-green-600 text-white hover:bg-green-500 transition-all"><i class="fa-solid fa-eye text-xs"></i></button>
                                    <button onclick="editItem('<?=$encData?>')" class="w-8 h-8 rounded bg-blue-600 text-white hover:bg-blue-500 transition-all"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                                    <button onclick="confirmDeleteWithTitle(<?=$row['id']?>,'<?=htmlspecialchars($row['title'],ENT_QUOTES)?>','portfolio.php')" class="w-8 h-8 rounded bg-red-600 text-white hover:bg-red-500 transition-all"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="saveModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-2xl rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-800 bg-slate-950 flex justify-between items-center"><h3 class="text-lg font-black text-white uppercase tracking-tighter" id="modalTitle">Portfolio Entry</h3><button onclick="closeModal('saveModal')" class="text-slate-500 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button></div>
        <form method="POST" enctype="multipart/form-data" class="p-8 space-y-4">
            <input type="hidden" name="id" id="port_id"><input type="hidden" name="existing_image" id="port_existing_image">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Title</label><input type="text" name="title" id="port_title" required class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Category</label><select name="category" id="port_category" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm focus:border-sky-500 outline-none">
                    <?php mysqli_data_seek($cat_list,0); while($c = mysqli_fetch_assoc($cat_list)): ?><option value="<?= $c['name'] ?>"><?= $c['name'] ?></option><?php endwhile; ?></select></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Project URL</label><input type="text" name="url" id="port_url" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Window Target</label><select name="target" id="port_target" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm"><option value="_blank">_blank (New Tab)</option><option value="_self">_self (Same Window)</option></select></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Price</label><input type="text" name="price" id="port_price" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-green-500 text-sm font-mono"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">SEO Tags</label><input type="text" name="seo_tag" id="port_seo_tag" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-sky-500 text-sm"></div>
            </div>
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Project Image</label><input type="file" name="portfolio_img" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-slate-500 text-xs"></div>
            <div><label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Description</label><textarea name="description" id="port_desc" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-4 py-2.5 text-white text-sm"></textarea></div>
            <div class="flex gap-3 pt-4"><button type="submit" name="save_portfolio" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg hover:bg-green-500 transition-all">Confirm Ok</button><button type="button" onclick="closeModal('saveModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase hover:bg-red-500 transition-all">Cancel</button></div>
        </form>
    </div>
</div>

<div id="previewModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-2xl rounded-2xl border border-slate-800 shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div id="previewImgView" class="w-full h-48 bg-slate-950 border-b border-slate-800"></div>
        <div class="p-8 space-y-6 overflow-y-auto flex-1">
            <div class="flex justify-between items-start"><div id="previewDataHeader"></div><div class="text-right"><p id="prev_id" class="text-sky-500 font-bold font-mono"></p><p id="prev_date" class="text-[9px] text-slate-500 font-bold uppercase"></p></div></div>
            <div id="previewDataGrid" class="grid grid-cols-2 gap-6 border-y border-slate-800/50 py-6"></div>
            <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-2">Project Description</label><div id="previewDataFooter" class="bg-slate-950 p-4 rounded-lg border border-slate-800 text-slate-400 text-sm leading-relaxed"></div></div>
            <button onclick="closeModal('previewModal')" class="w-full bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase shadow-lg hover:bg-green-500 transition-all">Close Preview</button>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="bg-slate-900 w-full max-w-md rounded-2xl p-8 border border-slate-800 text-center shadow-2xl">
        <div class="w-16 h-16 bg-red-600/10 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-600/20"><i class="fa-solid fa-triangle-exclamation text-2xl"></i></div>
        <h3 class="text-xl font-black text-white mb-2 uppercase tracking-tighter">Confirm Delete?</h3>
        <p class="text-slate-400 text-sm mb-8">Item: <span id="del_item_name" class="text-white font-bold underline"></span></p>
        <div class="flex gap-3"><a id="delConfirm" href="#" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-bold text-sm uppercase text-center leading-[48px] hover:bg-green-500 transition-all">Confirm Ok</a><button onclick="closeModal('deleteModal')" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold text-sm uppercase hover:bg-red-500 transition-all">Cancel</button></div>
    </div>
</div>

</main> <script src="js/main.js"></script>
<script>
    const colorPalette=['bg-sky-600 border-sky-400','bg-emerald-600 border-emerald-400','bg-rose-600 border-rose-400','bg-amber-600 border-amber-400','bg-indigo-600 border-indigo-400','bg-purple-600 border-purple-400'];
    function getJsTagColor(str){let hash=0;for(let i=0;i<str.length;i++)hash=str.charCodeAt(i)+((hash<<5)-hash);return colorPalette[Math.abs(hash)%colorPalette.length];}
    function toggleSidebar(){document.getElementById('sidebar').classList.toggle('-translate-x-full');document.getElementById('overlay').classList.toggle('hidden');}
    function openModal(id){document.getElementById(id).classList.remove('hidden');}
    function closeModal(id){document.getElementById(id).classList.add('hidden');}
    function prepareNew(){
        document.getElementById('port_id').value=''; document.getElementById('port_existing_image').value='';
        document.getElementById('port_title').value=''; document.getElementById('port_url').value='';
        document.getElementById('port_desc').value=''; document.getElementById('port_price').value='';
        document.getElementById('port_seo_tag').value=''; document.getElementById('port_target').value='_blank';
        document.getElementById('modalTitle').innerText='Add New Portfolio';
        openModal('saveModal');
    }
    function editItem(encoded){
        const d=JSON.parse(atob(encoded));
        document.getElementById('port_id').value=d.id; document.getElementById('port_existing_image').value=d.image_url;
        document.getElementById('port_title').value=d.title; document.getElementById('port_category').value=d.category;
        document.getElementById('port_url').value=d.url; document.getElementById('port_target').value=d.target;
        document.getElementById('port_price').value=d.price; document.getElementById('port_seo_tag').value=d.seo_tag;
        document.getElementById('port_desc').value=d.description; document.getElementById('modalTitle').innerText='Edit Portfolio #'+d.id;
        openModal('saveModal');
    }
    function previewItem(encoded){
        const d=JSON.parse(atob(encoded));
        document.getElementById('prev_id').innerText='#'+d.id;
        document.getElementById('prev_date').innerText='Created: '+d.created_at;
        document.getElementById('previewImgView').innerHTML=`<img src="../${d.image_url}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x200?text=NA'">`;
        document.getElementById('previewDataHeader').innerHTML=`<h4 class="text-white font-black uppercase text-2xl tracking-tighter">${d.title}</h4><p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">${d.category}</p>`;
        document.getElementById('previewDataGrid').innerHTML=`
            <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Price</label><p class="text-green-500 font-bold text-lg font-mono">${d.price}</p></div>
            <div><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Target Window</label><p class="text-slate-300 text-sm font-mono">${d.target}</p></div>
            <div class="col-span-2"><label class="text-[9px] text-slate-500 uppercase font-black block mb-1">Project URL</label><p class="text-sky-500 text-xs break-all">${d.url}</p></div>
            <div class="col-span-2"><label class="text-[9px] text-slate-500 uppercase font-black mb-2 block">SEO Meta Tags</label><div class="flex flex-wrap gap-1">${d.seo_tag.split(',').map(tag=>tag.trim()?`<span class="${getJsTagColor(tag.trim())} text-white font-bold text-[11px] px-2 py-0.5 rounded border">${tag.trim()}</span>`:'').join('')}</div></div>`;
        document.getElementById('previewDataFooter').innerHTML=d.description;
        openModal('previewModal');
    }
    function confirmDeleteWithTitle(id, title, page) {
        document.getElementById('del_item_name').innerText = '"' + title + '"';
        document.getElementById('delConfirm').href = page + '?delete_id=' + id;
        openModal('deleteModal');
    }
    document.getElementById('ajaxSearch').addEventListener('input',function(){
        fetch('portfolio.php?search_query='+encodeURIComponent(this.value)).then(r=>r.text()).then(html=>{
            document.getElementById('portfolioTableBody').innerHTML=html;
        });
    });
</script>
</body>
</html>
<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        Add Product
                    </div>
                    <div class="card-body">
                        <form action="" id="manage-add-product">
                            <input type="hidden" name="id">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" id="category" class="form-select browser-default">
                                    <?php
                                    $conn->select_db('central_db');
                                    $cat = $conn->query("SELECT * FROM category_list ORDER BY name ASC");
                                    while ($row = $cat->fetch_assoc()) {
                                        $cat_arr[$row['id']] = $row['name'];
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="product_name">
                            </div>
                            <div class="mb-3 save_image">
                                <label for="img" class="form-label" style=" text-align:left">Shop Image</label>
                                <input type="file" name="img" id="img" class="form-control">
                            </div>
                            <div class="mb-3 edit_image" style="display: none;">
                            <img id="product_img" name="product_img" src="" alt="Product Image" style="width: 150px; height: 100px;">
                            <input type="text" name="img" id="img" value="" hidden>
                            </div>
                        
                            <div class="mb-3">
                                <button class="btn btn-sm btn-primary col-sm-3 offset-md-3 mb-2"> Save</button>
                                <button class="btn btn-sm btn-danger col-sm-3 mb-2" type="button" onclick="$('#manage-product').get(0).reset(); $('.save_image').show(); $('.edit_image').hide();">Cancel</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-bordered border-warning table-info">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Category</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $conn->select_db('central_db');
                                    $cats = $conn->query("SELECT * FROM products order by id asc");
                                    while ($row = $cats->fetch_assoc()) :
                                    ?>
                                        <tr>
                                            <td scope="row"><?php echo $i++ ?></td>
                                            <td>
                                                <?php echo $row['name'] ?>
                                            </td>
                                            <td style="width: 100px; height: 60px;"><img src="<?php echo $row['image'] != '' ? 'assets/img/' . $row['image'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100%"></td>

                                            <td>
                                                <?php echo $cat_arr[$row['category_id']] ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit_product mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-category_id="<?php echo $row['category_id'] ?>" data-img="<?php echo $row['image'] ?>">Edit</button>
                                                <button class="btn btn-sm btn-danger delete_product mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>
<style>
    td {
        vertical-align: middle !important;
    }
    
	td p {
		margin: unset;
	}
</style>
<script>
	

	$('#manage-add-product').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=add_product',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				console.log(resp)
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	})
    $(document).ready(function() {
        $('table').dataTable()
		$(".save_image").show();
        $(".edit_image").hide();
	})
	$(document).on('click', '.edit_product', function() {
		start_load()
		var cat = $('#manage-add-product')
		cat.get(0).reset()
        $(".save_image").hide();
        $(".edit_image").show();
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='product_name']").val($(this).attr('data-name'))
        var imgPath = 'assets/img/' + $(this).attr('data-img');
        $("[name='product_img']").attr('src', imgPath);
        cat.find('input[name="img"][type="text"]').val($(this).attr('data-img'));		cat.find("[name='category_id']").val($(this).attr('data-category_id'))
		end_load()
	})
	$(document).on('click', '.delete_product', function() {
		_conf("Are you sure to delete this product?", "remove_product", [$(this).attr('data-id')])
	})

	function delete_product($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=remove_product',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				console.log(resp);
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
</script>
<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4 mb-3">
                <form action="" id="manage-add-product" enctype="multipart/form-data" method="POST">
                    <div class="card">
                        <div class="card-header">
                            <h5>Add Product</h5>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" id="category" class="form-select">
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
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                            <div class="mb-3 save_image">
                                <label for="img" class="form-label" style=" text-align:left">Shop Image</label>
                                <input type="file" name="img" id="img" class="form-control" accept=".jpg, .jpeg, .png, .gif">
                            </div>
                            <div class="mb-3 edit_image" style="display: none;">
                                <img id="product_img" name="product_img" src="" alt="Product Image" style="width: 150px; height: 100px;">
                                <input type="text" name="img" id="img" value="" hidden>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2"> Save</button>
                                    <button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-add-product').trigger('reset'); $('.save_image').show(); $('.edit_image').hide();">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4><b>Products</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-sm">
                            <table class="table table-striped table-bordered border-warning table-info">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Action</th>
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
                                            <td style="width: 100px; height: 60px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Preview"><img src="<?php echo $row['img_path'] != '' ? 'assets/img/' . $row['img_path'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100%"></td>

                                            <td>
                                                <?php echo $cat_arr[$row['category_id']] ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary edit_product mb-2" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-category_id="<?php echo $row['category_id'] ?>" data-img="<?php echo $row['img_path'] ?>">Edit</button>
                                                <button class="btn btn-sm btn-danger delete_product mb-2" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('table').dataTable()
        $(".save_image").show();
        $(".edit_image").hide();

        $('#manage-add-product').submit(function(e) {
            e.preventDefault()
            start_load()
            var fileInput = $('#img')[0]; // Access the DOM element
            var file = fileInput.files[0];

            if (file && file.size > 1 * 512 * 1024) { // 2MB in bytes
                alert_toast('The selected file is too large. Please select a file less than 512kb.', 'warning');
                end_load()
                return false;
            }
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
        });

        $(document).on('click', 'img', function() {
            var imgSrc = $(this).attr('src');
            image_modal(imgSrc);
        });

        $(document).on('click', '.edit_product', function() {
            start_load()
            var cat = $('#manage-add-product')
            $(".save_image").hide();
            $(".edit_image").show();
            cat.trigger('reset')
                .find("[name='id']").val($(this).data('id')).end()
                .find("[name='product_name']").val($(this).data('name')).end()
                .find('input[name="img"][type="text"]').val($(this).data('img')).end()
                .find("[name='category_id']").val($(this).data('category_id')).end()

            var imgPath = 'assets/img/' + $(this).data('img');
            $("#product_img").attr('src', imgPath);
            end_load()
        });

        $(document).on('click', '.delete_product', function() {
            _conf("Are you sure to delete this product?", "remove_product", [$(this).data('id')])
        })
    });

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
                } else {
                    alert_toast("Some error occur", 'danger');
                    setTimeout(function() {
                        location.reload()
                    }, 1500);
                }
            }
        })
    }
</script>
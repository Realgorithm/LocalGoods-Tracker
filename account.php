<?php
include('db_connect.php');
$conn->select_db('central_db');
$user = $conn->query("SELECT * FROM shops");
foreach ($user->fetch_array() as $k => $v) {
    $meta[$k] = $v;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4><b>Account Info</b></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped table-bordered border-warning table-info">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Cover image</th>
                                    <th scope="col">Tagline</th>
                                    <th scope="col">Shop_url</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"><?php echo isset($meta['shop_name']) ? $meta['shop_name'] : '' ?></td>
                                    <td><?php echo isset($meta['email']) ? $meta['email'] : '' ?></td>
                                    <td><?php echo isset($meta['contact']) ? $meta['contact'] : '' ?></td>
                                    <td style="width: 150px; height: 100px;"><img src="<?php echo $meta['cover_img'] != '' ? 'assets/img/' . $meta['cover_img'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100%"></td>
                                    <td><?php echo isset($meta['shop_tagline']) ? $meta['shop_tagline'] : '' ?></td>
                                    <td><?php echo isset($meta['shop_url']) ? $meta['shop_url'] : '' ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit_product" type="button" data-id="<?php echo $meta['id'] ?>" data-name="<?php echo $meta['shop_name'] ?>" data-email="<?php echo $meta['email'] ?>" data-contact="<?php echo $meta['contact'] ?>" data-about="<?php echo ($meta['shop_tagline']) ?>" data-img="<?php echo $meta['cover_img'] ?>">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Table Panel -->
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
        <!-- FORM Panel -->
        <form action="" id="manage-account" style="display: none;" enctype="multipart/form-data" method="POST">
            <div class="card">
                <div class="card-header">
                    Account Form
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Shop Name:</label>
                        <input type="text" id="name" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" step="any" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cover Image:</label>
                        <input type="file" step="any" class="form-control" name="img">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact:</label>
                        <input type="text" class="form-control" step="any" name="contact">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagline:</label>
                        <textarea class="form-control" cols="30" rows="3" name="about"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2"> Save</button>
                            <button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-account').get(0).reset()"> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- FORM Panel -->
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
    $('.edit_product').click(function() {

        start_load()
        var cat = $('#manage-account')
        cat.get(0).reset()
        cat.find("[name='id']").val($(this).attr('data-id'))
        cat.find("[name='name']").val($(this).attr('data-name'))
        cat.find("[name='email']").val($(this).attr('data-email'))
        // Decode HTML entities for the 'about' field
        cat.find("[name='about']").val($(this).attr('data-about'));
        cat.find("[name='contact']").val($(this).attr('data-contact'))
        // cat.find("[name='img']").val($(this).attr('data-img'))
        cat.css('display', 'block'); // Change display property to block
        end_load()
    })
    $('#manage-account').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_settings',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                // //console.log(resp)
                if (resp == 1) {
                    alert_toast("Data successfully updated", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    })
   $(document).ready(function() {
        $(document).on('click', 'img', function() {
            var imgSrc = $(this).attr('src');
            image_modal(imgSrc);
        });
    })
</script>
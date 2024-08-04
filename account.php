<?php
include('db_connect.php');
$conn->select_db('central_db');
$url = $_SESSION['shop_url'];
$user = $conn->query("SELECT * FROM shops where shop_url='" . $url . "'")->fetch_assoc();
foreach ($user as $k => $v) {
    $$k = $v;
}
echo $_SESSION['shop_url']
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
                            <colgroup>
                                <col width="20%">
                                <col width="20%">
                                <col width="15%">
                                <col width="10%">
                                <col width="15%">
                                <col width="15%">
                                <col width="5%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Cover Image</th>
                                    <th scope="col">Tagline</th>
                                    <th scope="col">Shop URL</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"><?php echo isset($shop_name) ? $shop_name : ''; ?></td>
                                    <td><?php echo isset($email) ? $email : ''; ?></td>
                                    <td><?php echo isset($contact) ? $contact : ''; ?></td>
                                    <td style="width: 150px; height: 100px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Preview">
                                        <img src="<?php echo $cover_img ? 'assets/img/' . $cover_img : 'assets/img/1600398180_no-image-available.png'; ?>" alt="" width="100%" height="100%">
                                    </td>
                                    <td><?php echo isset($shop_tagline) ? $shop_tagline : ''; ?></td>
                                    <td><?php echo isset($shop_url) ? $shop_url : ''; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit_product" type="button" data-id="<?php echo $id; ?>" data-name="<?php echo $shop_name; ?>" data-email="<?php echo $email; ?>" data-contact="<?php echo $contact; ?>" data-about="<?php echo $shop_tagline; ?>" data-img="<?php echo $cover_img; ?>">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
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
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3" >
                        <label class="form-label" >Cover Image:</label>
                        <input type="file" class="form-control" name="img" id="img" accept=".jpg, .jpeg, .png, .gif">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact:</label>
                        <input type="text" class="form-control" name="contact">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagline:</label>
                        <textarea class="form-control" cols="30" rows="3" name="about"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary col-sm-4 offset-md-1 mb-2 me-2">Save</button>
                            <button class="btn btn-sm btn-danger col-sm-4 offset-md-1 mb-2" type="button" onclick="$('#manage-account').trigger('reset').hide();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(document).on('click', '.edit_product', function() {
            start_load();
            var cat = $('#manage-account');
            cat.trigger('reset')
                .find("[name='id']").val($(this).data('id')).end()
                .find("[name='name']").val($(this).data('name')).end()
                .find("[name='email']").val($(this).data('email')).end()
                .find("[name='about']").val($(this).data('about')).end()
                .find("[name='contact']").val($(this).data('contact')).end()
                .css('display', 'block').end()
            end_load();
        });

        $('#manage-account').submit(function(e) {
            e.preventDefault();
            start_load();

            var fileInput = $('#img')[0]; // Access the DOM element
            var file = fileInput.files[0];

            if (file && file.size > 1 * 1024 * 1024) { // 2MB in bytes
                alert_toast('The selected file is too large. Please select a file less than 1MB.', 'warning');
                end_load()
                return false;
            }

            $.ajax({
                url: 'ajax.php?action=save_settings',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Data successfully added", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);

                    } else if (resp == 2) {
                        alert_toast("Data successfully updated", 'success')
                        setTimeout(function() {
                            location.reload()
                        }, 1500)

                    }
                }
            });
        });

        $(document).on('click', 'img', function() {
            var imgSrc = $(this).attr('src');
            image_modal(imgSrc);
        });
    });
</script>
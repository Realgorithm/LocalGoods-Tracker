<?php
include('db_connect.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4><b><i class="fa fa-store-alt"></i> Shops Info</b></h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-striped table-bordered border-warning table-info">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Cover image</th>
                                    <th scope="col">Tagline</th>
                                    <th scope="col">Shop_url</th>
                                    <th scope="col">Db Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $conn->select_db('central_db');
                                $user = $conn->query("SELECT * FROM shops");
                                while ($meta = $user->fetch_array()) : ?>
                                    <tr>
                                        <td scope="row"><?php echo $i++ ?></td>
                                        <td><?php echo isset($meta['shop_name']) ? $meta['shop_name'] : '' ?></td>
                                        <td><?php echo isset($meta['email']) ? $meta['email'] : '' ?></td>
                                        <td><?php echo isset($meta['contact']) ? $meta['contact'] : '' ?></td>
                                        <td style="width: 150px; height: 100px;" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Preview"><img src="<?php echo $meta['cover_img'] != '' ? 'assets/img/' . $meta['cover_img'] : 'assets/img/1600398180_no-image-available.png' ?>" alt="" width="100%" length="100%"></td>
                                        <td><?php echo isset($meta['shop_tagline']) ? $meta['shop_tagline'] : '' ?></td>
                                        <td><?php echo isset($meta['shop_url']) ? $meta['shop_url'] : '' ?></td>
                                        <td><?php echo isset($meta['db_name']) ? $meta['db_name'] : '' ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-danger delete_shop mb-2" href="javascript:void(0)" data-id="<?php echo $meta['id'] ?>">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Panel -->
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
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
    $(document).ready(function() {
        $('table').dataTable()

        $(document).on('click', 'img', function() {
            var imgSrc = $(this).attr('src');
            image_modal(imgSrc);
        });
    })
    $(document).on('click', '.delete_shop', function() {
        _conf("Are you sure to delete this shop data?", "delete_shop", [$(this).data('id')])
    })

    function delete_shop($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_shop',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                //console.log(resp)
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                } else if (resp == 2) {
                    alert_toast("User deleted, but profile picture not found.", 'warning');
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                } else {
                    alert_toast("An error occurred. Please try again.", 'danger');
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>
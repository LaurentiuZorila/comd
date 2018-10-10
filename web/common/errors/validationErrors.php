<?php
?>
<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-body">
                <div class="alert alert-dismissible fade show badge-danger" role="alert">
                    <strong class="text-white"> You have some errors! </strong>
                    <?php
                    foreach ($validation->errors() as $errors) { ?>
                        <p class="text-white mb-0"><?php echo  $errors; ?></p>
                    <?php } ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
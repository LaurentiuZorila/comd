<?php
?>

<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-body">
                <div class="alert alert-dismissible fade show badge-info" role="alert">
                    <strong class="text-white"> Success! </strong>
                    <p class="text-white mb-0"> <?php echo Session::flash('profileUpdateSuccess'); ?></p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
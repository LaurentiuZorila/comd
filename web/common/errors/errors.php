<?php

if (Errors::countAllErrors()) { ?>
    <section>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-body">
                    <div class="alert alert-dismissible fade show badge-<?php echo Errors::getErrorType(); ?>" role="alert">
                        <strong class="text-white"> <?php echo Translate::t(Errors::errorMessage()); ?> </strong>
                        <?php
                        foreach (Errors::getErrors() as $errors ) { ?>
                            <p class="text-white mb-0"> <?php echo $errors; ?> </p>
                        <?php } ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>


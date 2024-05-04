<div class="container mb-5" id="recentlyContainer">
    <div class="card add-shadow-2">
        <div class="card-header bg-success text-white text-center">
            <h3 class="m-0">The Last Added Contacts</h3>
        </div>

        <?php if ($recently_added_data['name'] === 1) : ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 m-auto">
                        <div class="card add-shadow-4">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col text-center">
                                        <?php echo $recently_added_data['error']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8 col-md-6 col-lg-4 col-xl-4 col-11 m-auto">
                        <div class="card add-shadow-4">
                        <img src="<?= site_url($recently_added_data['image_location']) ?>" class="card-img-top img-fluid custom-card-img" alt="...">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col">
                                        <div class="card-label" for="name">Name:</div>
                                        <p class="card-text truncate" id="name"><?php echo $recently_added_data['name']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card-label" for="address">Address:</div>
                                        <p class="card-text truncate" id="address"><?php echo $recently_added_data['address']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card-label" for="email">Email:</div>
                                        <p class="card-text truncate" id="email"><?php echo $recently_added_data['email']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card-label" for="phone">Phone:</div>
                                        <p class="card-text truncate" id="phone"><?php echo $recently_added_data['phone_number']; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card-label" for="remark">Remark:</div>
                                        <p class="card-text truncate" id="remark"><?php echo $recently_added_data['remark']; ?></p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-4 col-6 d-flex justify-content-center align-items-center m-auto">
                                        <button value="<?php echo $recently_added_data['phone_number']; ?>" id="recentEditButton" class="btn btn-success btn-lg btn-block border-line-1" onclick="editContact('<?php echo $recently_added_data['phone_number']; ?>')">Edit</button> 
                                    </div>
                                    <div class="col-sm-4 col-6 d-flex justify-content-center align-items-center m-auto">
                                    <button value="<?php echo $recently_added_data['phone_number']; ?>" id="recentDeleteButton" class="btn btn-danger btn-lg btn-block border-line-2" onclick="deleteContact('<?php echo $recently_added_data['phone_number']; ?>')">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>


    </div>
</div>
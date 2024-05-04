<!-- 

  HTML ID: contactView
  Usage  : Container that contains contact 

  pagination

  HTML ID: currentPage
  Usage  : Keep Track of page number that user is viewing

-->

<div class="container" id="contactView">

    <?php if ($total_number_of_contact == null) : ?>

        <div class="row text-center add-shadow-4">
            <div class="col-12 bg-primary text-white">
                <h2>Contacts</h3>
            </div>
            <div class="col-12 bg-info text-white add-text-shadow-1">
                <h3>Number of Contacts : 0 </h3>
            </div>
            <div class="col-12 bg-white text-dark add-shadow-2 m-auto">
                <h5>Current Page : <span id="currentPage">1</span>  </h5>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 col-md-6 col-lg-6 col-xl-6 col-11 m-auto">
                <div class="card add-shadow-4">
                    <div class="card-body text-center">
                        <?php echo $all_contact_data['error']; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php else : ?>

        <div class="row text-center add-shadow-4">
            <div class="col-12 bg-primary text-white">
                <h2>Contacts</h3>
            </div>
            <div class="col-12 bg-info text-white add-text-shadow-1">
                <h4>Total : <?php echo count($total_number_of_contact); ?> </h4>
            </div>
            <div class="col-12 bg-white text-dark add-shadow-2 m-auto">
                <h5>Current Page : <span id="currentPage">1</span>  </h5>
            </div>
        </div>
        
        <div class="row">
        <?php foreach($all_contact_data as $contact): ?>
            <div class="col-sm-6 col-lg-3 col-xl-3 col-11 m-auto">
                <div class="card add-shadow-4">
                    <img src="<?= site_url($contact['image_location']) ?>" class="card-img-top img-fluid custom-card-img" alt="...">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col">
                                <div class="card-label" for="name">Name:</div>
                                <p class="card-text truncate" id="name"><?php echo $contact['name']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-label" for="address">Address:</div>
                                <p class="card-text truncate" id="address"><?php echo $contact['address']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-label" for="email">Email:</div>
                                <p class="card-text truncate" id="email"><?php echo $contact['email']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-label" for="phone">Phone:</div>
                                <p class="card-text truncate" id="phone"><?php echo $contact['phone_number']; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="card-label" for="phone">Remark:</div>
                                <p class="card-text truncate" id="remark"><?php echo $contact['remark']; ?></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-sm-4 col-6 d-flex justify-content-center align-items-center m-auto">
                            <button value="<?php echo $contact['phone_number']; ?>" id="recentEditButton" class="btn btn-success btn-lg btn-block border-line-1" onclick="editContact('<?php echo $contact['phone_number']; ?>')">Edit</button> 
                            </div>
                            <div class="col-sm-4 col-6 d-flex justify-content-center align-items-center m-auto">
                            <button value="<?php echo $contact['phone_number']; ?>" id="recentDeleteButton" class="btn btn-danger btn-lg btn-block border-line-2" onclick="deleteContact('<?php echo $contact['phone_number']; ?>')">Delete</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>


        <div class="row mb-3">
            <div class="col-12 text-center">
                Total Pages : <b> <?php echo $total_number_of_page; ?> </b>
            </div>
            <div class="col-12">
                <div class="pagination" id="pagination">

                        <?php echo $pagination_data; ?>

                </div>
            </div>
        </div>

        <?php if($total_number_of_contact > 0 ) : ?>
        <div class="row">
            <div class="col-12 text-center d-flex justify-content-center align-items-center m-auto">
                <div class="page-input input-group add-shadow-4">

                <input type="text" class="form-control text-center" id="pageInput" placeholder="Page...">
                <button class="btn btn-danger" type="button" onclick="gotoPage(document.getElementById('pageInput').value)">Go</button>

                </div>
            </div>
        </div>
        <?php else : ?>
        <?php endif; ?>
        

    <?php endif; ?>
</div>
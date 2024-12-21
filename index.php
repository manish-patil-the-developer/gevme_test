<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gevme Test!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        .teal {
            background-color: #009688 !important;
        }

        footer.page-footer p,
        footer.page-footer h5,
        footer.page-footer a {
            bottom: 0;
            color: #fff;
            font-family: revert;
        }

        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px;
            line-height: 60px;
            background-color: #f5f5f5;
        }
    </style>
</head>

<body>

    <!-- HEADER: MENU + HEROE SECTION -->
    <header>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="">Home <span class="sr-only">(current)</span></a>
                    </li>
                </ul>
            </div> -->
        </nav>
    </header>

    <!-- CONTENT -->
    <div class="container-fluid">

        <div class="d-none justify-content-center" role="alert" id="response-alert">
            <span class=" alert alert-primary text-center w-25" id="response-message">This is a primary alert—check it out!</span>
        </div>
        <form action="" id="gevme_form" name="gevme_form" method="post" enctype="multipart/form-data">

            <div class="row">
                <!-- <div class="form-group col-md-2 text-center align-middle">
                    <label for="">
                        Contact Us
                    </label>

                </div> -->

                <div class="form-group col-md-3">
                    <label for="st_full_name">
                        Full Name
                    </label>
                    <input class="form-control form-control-sm" type="text" id="st_full_name" name="st_full_name" placeholder="" value="<?php if (isset($movie_detail['st_movie_name']) && !empty($movie_detail['st_movie_name'])) {
                                                                                                                                            echo $movie_detail['st_movie_name'];
                                                                                                                                        } ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="st_email">
                        Email
                    </label>
                    <input class="form-control form-control-sm" type="email" id="st_email" name="st_email" placeholder="" value="<?php if (isset($movie_detail['st_movie_name']) && !empty($movie_detail['st_movie_name'])) {
                                                                                                                                        echo $movie_detail['st_movie_name'];
                                                                                                                                    } ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="in_contact_number">
                        Phone
                    </label>

                    <input class="form-control form-control-sm" type="text" id="in_contact_number" name="in_contact_number" placeholder="">
                </div>

                <div class="form-group col-md-3">
                    <button type="submit" class="btn btn btn-success my-3" id="submit_button" data-submittype="add">Submit</button>
                </div>

            </div>

        </form>


        <table class="table table-sm" id="gevme_listing">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Revisit</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="update_listing_rows">

                
            </tbody>
        </table>


    </div>

    <div class="modal" id="DeleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                </div>
                <div class="modal-body p-3">
                    <p class="mb-0">Are you sure you want to delete this unit?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline mr-2" data-dismiss="modal">Cancel</button>
                    <button type="button" id="delete_user" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER: DEBUG INFO + COPYRIGHTS -->

    <!-- Footer -->
    <footer class="page-footer font-small teal">

        <!-- Copyright -->
        <div class="container text-center">
            <span class="">© 2020 Copyright: @manish_patil</span>
        </div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->

    <!-- SCRIPTS -->
    <script>
        var base_url = "../gevme_test/";
    </script>

    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


    <script src="../gevme_test/js/jquery.slimscroll.js"></script>
    <script src="../gevme_test/js/jquery.slimscroll.min.js"></script>
    <script src="../gevme_test/js/jquery.validate.min.js"></script>
    <script src="../gevme_test/js/form_validate.js"></script>

    <script>
        function toggleMenu() {
            var menuItems = document.getElementsByClassName('menu-item');
            for (var i = 0; i < menuItems.length; i++) {
                var menuItem = menuItems[i];
                menuItem.classList.toggle("hidden");
            }
        }
    </script>

</body>

</html>
@masterSection(data=[file|board,name|content])end


<!-- Portfolio Grid Section -->
<section class="portfolio" id="portfolio">
    <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Portfolio</h2>
        <hr class="star-dark mb-5">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <a class="portfolio-item d-block mx-auto text-center" href="#portfolio-modal-1">
                    <i class="fa fa-star fa-5x" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a class="portfolio-item d-block mx-auto text-center" href="#portfolio-modal-2">
                    <i class="fa fa-star fa-5x" aria-hidden="true"></i>
                </a>
            </div>
            <div class="col-md-12 col-lg-4">
                <a class="portfolio-item d-block mx-auto text-center" href="#portfolio-modal-3">
                    <i class="fa fa-star fa-5x" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="bg-primary text-white mb-0" id="about">
    <div class="container">
        <h2 class="text-center text-uppercase text-white">About</h2>
        <hr class="star-light mb-5">
        <div class="row">
            <div class="col-lg-12 ml-auto">
                <p class="lead">
                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact">
    <div class="container">
        <h2 class="text-center text-uppercase text-secondary mb-0">Contact Me</h2>
        <hr class="star-dark mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                <form name="sentMessage" id="contactForm" novalidate="novalidate">
                    <div class="control-group">
                        <div class="form-group floating-label-form-group form-group-fullname controls mb-0 pb-2">
                            <label>Full Name</label>
                            <input class="form-control" id="fullname" type="text" name="fullname" placeholder="Full Name" required="required" data-validation-required-message="Please enter your full name.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group form-group-email controls mb-0 pb-2">
                            <label>Email Address</label>
                            <input class="form-control" id="email" type="email" name="email" placeholder="Email Address" required="required" data-validation-required-message="Please enter your email address.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group form-group-phone controls mb-0 pb-2">
                            <label>Phone Number</label>
                            <input class="form-control" id="phone" type="tel" name="phone" placeholder="Phone Number" required="required" data-validation-required-message="Please enter your phone number.">
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group form-group-message controls mb-0 pb-2">
                            <label>Message</label>
                            <textarea class="form-control" id="message" rows="5" name="message" placeholder="Message" required="required" data-validation-required-message="Please enter a message."></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="form-message"></div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-xl" id="sendMessageButton">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        $('form').submit(function () {
            return false;
        });
    });</script>
<script>
    var obj;
    $(document).ready(function () {
        $("button#sendMessageButton").on('click', function () {
            onClickSubmitDisabled();
            $.ajax({
                url: "ajax/checkDataForm",
                type: "POST",
                dataType: "json",
                data: {fullname: $("input[name='fullname']").val(), phone: $("input[name='phone']").val(), email: $("input[name='email']").val(), message: $("textarea[name='message']").val()},
                beforeSend: function () {

                },
                success: function (response) {
                    if (response) {
                        saveData(response);
                        valids(response);
                        onClickSubmitActive();
                    } else {
                    }
                }
            });
        });
    });</script>
<script>
    function valids(obj) {
        var objr = obj.dataHttpRequest;
        for (var prop in objr) {
            if (!objr.hasOwnProperty(prop))
                continue;
            if (!objr[prop].err) {
                $(".form-group-" + prop).removeClass("has-error");
                $(".form-group-" + prop).addClass("has-success");
                $(".form-group-" + prop + " .help-block").html('');
            } else {
                console.log(objr[prop].message_err);
                $(".form-group-" + prop).removeClass("has-success");
                $(".form-group-" + prop).addClass("has-error");
                $(".form-group-" + prop + " .help-block").html(objr[prop].message_err);
            }
        }
    }
    function saveData(obj) {
        if (obj.isProper) {
            location.href = "thank-you";
            /*$('.form-message').html('<div class="alert alert-success"><strong>Data sent successfully</strong></div>');*/
            $("input[name='submit']").css('display', 'none');
        }
    }
</script>
<script>
    function onClickSubmitDisabled() {
        $("button#sendMessageButton").addClass('disabled');
        $("button#sendMessageButton").val(' Sending information, please wait ... ');
    }
    function onClickSubmitActive() {
        $("button#sendMessageButton").removeClass('disabled');
        $("button#sendMessageButton").val('Send');
    }
</script>
<?php 
session_start();
include "./views/navbar.php" ;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oriental Outsource</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4" id="Home">
        <section>
            <h1 class="text-center">Welcome to Oriental Outsourcing Consultants Private Limited</h1>
        </section>
        <section class="row mt-0 p-0">
            <div class="col-12 col-md-3 d-flex justify-content-center align-items-center mt-3">
                <img src="https://orientaloutsourcing.com/wp-content/uploads/2024/01/20YEARS1.png" alt="" class="img-thumbnail">
            </div>
            <div class="col-12 col-md-9" id="About">
                <h2 class="mb-2 mt-3">About Us</h2>
                <p>Oriental Outsourcing stands out as a prominent software development company that specializes in providing customized digital solutions to businesses worldwide. Our dedicated team comprises 50+ full-stack developers, designers, and innovators who have successfully designed and developed over 100 digital solutions across diverse industry verticals. By fostering close collaboration among our designers, and full-stack developers, we engage in collective research and development endeavors to create innovative applications and solutions that align seamlessly with the evolving technological landscape.</p>
                <p class="my-0">As a technology pioneer, Oriental Outsourcing possesses extensive knowledge and expertise in web development, app development, UI/UX design, and website maintenance. We are committed to assisting companies in conquering their most intricate technological challenges and driving their business growth</p>
            </div>
        </section>
        <section class="my-4 row p-0" id="Services">
            <h2>Services</h2>
            <div class="row m-auto w-100 g-1 r-1"> 
                <div class="card col-md-4 p-1" style="border:none;">
                    <div class="p-3 card-body rounded" style="border:4px solid lightgrey;">
                            <h5 class="card-title">Web Development</h5>
                            <p class="card-text">Transform your website into a viable source of traffic and revenue with our state-of-the-art web development services.</p>
                    </div>
                </div>
                <div class="card col-md-4 p-1" style="border:none;">
                    <div class="p-3 card-body rounded" style="border:4px solid lightgrey;">
                            <h5 class="card-title">UI/UX Design</h5>
                            <p class="card-text">Develop the products you've visualized with the help of our experienced designers. We rely on proven standards and twenty years of experience.</p>
                    </div>
                </div>
                <div class="card col-md-4 p-1" style="border:none;">
                    <div class="p-3 card-body rounded" style="border:4px solid lightgrey;">
                            <h5 class="card-title">Mobile App Development</h5>
                            <p class="card-text">Meet your demanding timeframes with our mobile app development services that combine a mobile platform, custom development, and cutting-edge technology.</p>
                    </div>
                </div>
                <div class="card col-md-4 p-1" style="border:none;">
                    <div class="p-3 card-body rounded" style="border:4px solid lightgrey;">
                            <h5 class="card-title">E-Commerce Development</h5>
                            <p class="card-text">Boost the performance and efficiency of your store with the help of our all-inclusive eCommerce development services & solutions.</p>
                    </div>
                </div>
                <div class="card col-md-4 p-1" style="border:none;">
                    <div class="p-3 card-body rounded" style="border:4px solid lightgrey;">
                            <h5 class="card-title">Digital Marketing</h5>
                            <p class="card-text">Craft a large customer flow towards your brand with our digital marketing services. Our experts excel in SEO, PPC, SMO, & much more to boost website traffic & gain more ROI.</p>
                    </div>
                </div>
                <div class="card col-md-4 p-1" style="border:none;">
                    <div class="p-3 card-body rounded" style="border:4px solid lightgrey;">
                            <h5 class="card-title">QA And Testing</h5>
                            <p class="card-text">Automated software testing, established standards, simplified Q&A testing lifecycle - use our testing services to ensure the efficient functioning of your software services.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include './views/footer.php' ?>
</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include "./views/loader.php" ; ?> 
</html>
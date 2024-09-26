<?php
// Include header file
require_once 'templates/header.php';

$company_name = "AJ Real Estate";
$years_of_experience = 10;
$services = [
    "Property sales",
    "Rentals",
    "Property management",
];
$reasons_to_choose = [
    "Extensive market knowledge and expertise",
    "Personalized service tailored to your needs",
    "Cutting-edge technology for property searches and marketing",
    "Transparent and ethical business practices",
    "Ongoing support even after the transaction is complete",
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About <?php echo $company_name; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <main>
        <div class="relative bg-cover bg-center bg-fixed" style="background-image: url('/uploads/aboutbg.jpg');">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <div class="relative z-10 py-24">
                <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 mb-11">
                    <div class="bg-[#166534] bg-opacity-80 rounded-xl p-8 shadow-xl">
                        <h1 class="text-5xl font-bold text-center text-white mb-6"><?php echo $company_name; ?></h1>
                        <p class="text-xl text-white text-center mb-8">
                            With over <?php echo $years_of_experience; ?> years of experience, we're committed to excellence in real estate services.
                        </p>
                    </div>

                    <div class="mt-16 bg-white bg-opacity-80 backdrop-filter backdrop-blur-lg rounded-lg shadow-lg p-8 relative" style="background-image: url('/uploads/aboutbg.jpg'); background-size: cover; background-position: center;">
                        <div class="relative z-10">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <div>
                                    <h2 class="text-4xl font-bold text-[#166534] mb-6">About Us</h2>
                                    <p class="text-lg text-gray-800 mb-6">
                                        <?php echo $company_name; ?> has been a trusted name in the property market for over <?php echo $years_of_experience; ?> years. Our commitment to excellence and personalized service has made us a leader in the industry.
                                    </p>
                                    <p class="text-lg text-gray-800 mb-6">
                                        We specialize in residential and commercial properties, offering a wide range of services including:
                                        <ul class="list-disc list-inside ml-4 mt-2 text-gray-800">
                                            <?php foreach ($services as $service): ?>
                                                <li><?php echo $service; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </p>
                                    <p class="text-lg text-gray-800 mb-6 mt-5">
                                        Our team of experienced professionals is dedicated to helping our clients find their perfect home or investment opportunity. We understand that buying or selling a property is one of the most important decisions you'll make, and we go above and beyond to ensure that our clients receive the highest level of service and support throughout the entire process.
                                    </p>
                                    <p class="text-lg text-gray-800">
                                        Whether you're a first-time homebuyer, seasoned investor, or looking to sell your property, <?php echo $company_name; ?> is here to guide you every step of the way. Let us help you turn your real estate dreams into reality.
                                    </p>
                                </div>
                                <div class="relative">
                                    <img src="/uploads/aboutbg.jpg" alt="Property" class="w-full h-full object-cover rounded-lg shadow-lg">
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-white opacity-70 rounded-lg"></div>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-[#052e16] bg-opacity-80 rounded-lg shadow-xl p-8">
                            <h2 class="text-3xl font-bold text-white mb-4 mt-11">Our Mission</h2>
                            <p class="text-lg text-white">
                                To provide unparalleled real estate services, focusing on integrity, professionalism, and client satisfaction. We aim to make the process of buying, selling, or renting property as smooth and rewarding as possible.
                            </p>
                        </div>
                        <div class="bg-[#052e16] bg-opacity-80 rounded-lg shadow-xl p-8">
                            <h2 class="text-3xl font-bold text-white mb-4 mt-11">Why Choose Us?</h2>
                            <ul class="list-disc list-inside text-lg text-white">
                                <?php foreach ($reasons_to_choose as $reason): ?>
                                    <li><?php echo $reason; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>

<?php
// Include footer file
require_once 'templates/footer.php';
?>
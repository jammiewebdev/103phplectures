<?php
// Include header
include __DIR__ . '/templates/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="relative bg-gray-900 text-white py-32">
        <div class="absolute inset-0">
            <img src="uploads/contactphp.jpg" alt="Real Estate Agents" class="w-full h-full object-cover opacity-50">
        </div>
        <div class="relative container mx-auto px-4 z-10 flex flex-col md:flex-row justify-between items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-5xl font-bold mb-4">Our Expert Agents</h1>
                <p class="text-xl mb-8">Dedicated professionals committed to finding your perfect home</p>
                <a href="#meet-our-agents" class="bg-primary hover:bg-secondary text-white font-bold py-3 px-6 rounded-lg transition duration-300">Meet Our Team</a>
            </div>
            <div class="md:w-1/2 bg-primary bg-opacity-50 backdrop-filter backdrop-blur-md p-8 rounded-lg">
                <h2 class="text-4xl font-bold mb-4">Ready to Find Your Dream Home?</h2>
                <a href="contact.php" class="bg-white text-primary hover:bg-secondary hover:text-white font-bold py-3 px-6 rounded-lg transition duration-300">Contact Us Today</a>
            </div>
        </div>
    </section>

      <!-- Meet Our Agents Section -->
      <section id="meet-our-agents" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-primary">Meet Our Agents</h2>
            <div class="flex flex-col md:flex-row justify-center items-center space-y-8 md:space-y-0 md:space-x-8">
                <!-- Agent 1 -->
                <div class="w-full md:w-2/5 relative">
                    <img src="uploads/contactagent2.jpg" alt="Agent 1" class="w-full h-auto object-cover rounded-lg shadow-lg">
                    <div class="absolute top-0 right-0 mt-8 mr-8 w-1/2 text-right">
                        <div class="bg-black bg-opacity-50 p-4 rounded-lg mt-11">
                            <h3 class="text-xl font-semibold mb-2 text-white">Jammie Torayno</h3>
                            <p class="text-sm text-white mb-2">Residential Specialist</p>
                            <p class="text-xs text-white mb-4">With over 10 years of experience, Jammie specializes in finding the perfect family homes.</p>
                            <div class="flex justify-end space-x-2">
                                <div class="relative group">
                                    <svg class="w-6 h-6 text-white cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-white text-primary text-xs py-1 px-2 rounded">jammiet@ajrealestate.com</span>
                                </div>
                                <div class="relative group">
                                    <svg class="w-6 h-6 text-white cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-white text-primary text-xs py-1 px-2 rounded">(+63) 912-345-6789</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agent 2 -->
                <div class="w-full md:w-2/5 relative">
                    <img src="uploads/agentcontact.jpg" alt="Agent 2" class="w-full h-auto object-cover rounded-lg shadow-lg">
                    <div class="absolute top-0 right-0 mt-8 mr-8 w-1/2 text-right">
                        <div class="bg-black bg-opacity-50 p-4 rounded-lg mt-11">
                            <h3 class="text-xl font-semibold mb-2 text-white">Alexa Holmina</h3>
                            <p class="text-sm text-white mb-2">Luxury Property Expert</p>
                            <p class="text-xs text-white mb-4">Alexa's expertise lies in high-end properties and exclusive listings.</p>
                            <div class="flex justify-end space-x-2">
                                <div class="relative group">
                                    <svg class="w-6 h-6 text-white cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-white text-primary text-xs py-1 px-2 rounded">alexaholmina@ajrealestate.com</span>
                                </div>
                                <div class="relative group">
                                    <svg class="w-6 h-6 text-white cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="absolute bottom-full right-0 mb-2 hidden group-hover:block bg-white text-primary text-xs py-1 px-2 rounded">(+63) 917-876-5432</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Our Agents Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-primary">Why Choose Our Agents?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2 text-primary">Expertise</h3>
                    <p class="text-gray-600">Our agents have in-depth knowledge of the local real estate market and trends.</p>
                </div>
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    <h3 class="text-xl font-semibold mb-2 text-primary">Communication</h3>
                    <p class="text-gray-600">We prioritize clear, timely, and professional communication with our clients.</p>
                </div>
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <h3 class="text-xl font-semibold mb-2 text-primary">Negotiation Skills</h3>
                    <p class="text-gray-600">Our agents are skilled negotiators, ensuring you get the best possible deal.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Include footer
include __DIR__ . '/templates/footer.php';
?>
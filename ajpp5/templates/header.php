<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');

function isActive($page) {
    global $current_page;
    return $current_page === $page ? 'text-[#052e16] border-b-2 border-[#052e16]' : 'text-gray-600 hover:text-[#052e16] hover:border-[#052e16] border-b-2 border-transparent';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJ Real Estate</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2232%22 height=%2232%22 viewBox=%220 0 20 20%22><defs><linearGradient id=%22gradient%22 x1=%220%%22 y1=%220%%22 x2=%22100%%22 y2=%220%%22><stop offset=%220%%22 style=%22stop-color:%23052e16;stop-opacity:1%22 /><stop offset=%22100%%22 style=%22stop-color:%2314532d;stop-opacity:1%22 /></linearGradient></defs><rect width=%2220%22 height=%2220%22 rx=%222%22 fill=%22url%28%23gradient%29%22 /><path d=%22M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z%22 fill=%22white%22 /></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#052e16',
                        secondary: '#14532d',
                    },
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-white bg-opacity-70 backdrop-blur-md fixed w-full z-10 shadow-md">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-white bg-gradient-to-r from-primary to-secondary p-1 rounded" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="text-2xl font-bold text-primary ml-2">AJ Real Estate</span>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="index.php" class="<?php echo isActive('index'); ?> inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">Home</a>
                    <a href="about.php" class="<?php echo isActive('about'); ?> inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">About</a>
                    <a href="properties.php" class="<?php echo isActive('properties'); ?> inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">Properties</a>
                    <a href="agents.php" class="<?php echo isActive('agents'); ?> inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">Agents</a>
                    <a href="market-insights.php" class="<?php echo isActive('about'); ?> inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">Market Insights</a>
                    <a href="contact.php" class="<?php echo isActive('contact'); ?> inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-150 ease-in-out">Contact</a>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <a href="add_property.php" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">List Your Property</a>
                </div>
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden bg-white bg-opacity-70 backdrop-blur-md" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="index.php" class="<?php echo $current_page === 'index' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'; ?> block pl-3 pr-4 py-2 border-l-4 <?php echo $current_page === 'index' ? 'border-primary' : 'border-transparent'; ?> text-base font-medium">Home</a>
                <a href="about.php" class="<?php echo $current_page === 'about' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'; ?> block pl-3 pr-4 py-2 border-l-4 <?php echo $current_page === 'about' ? 'border-primary' : 'border-transparent'; ?> text-base font-medium">About</a>
                <a href="properties.php" class="<?php echo $current_page === 'properties' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'; ?> block pl-3 pr-4 py-2 border-l-4 <?php echo $current_page === 'properties' ? 'border-primary' : 'border-transparent'; ?> text-base font-medium">Properties</a>
                <a href="agents.php" class="<?php echo $current_page === 'agents' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'; ?> block pl-3 pr-4 py-2 border-l-4 <?php echo $current_page === 'agents' ? 'border-primary' : 'border-transparent'; ?> text-base font-medium">Agents</a>
                <a href="market-insights.php" class="<?php echo $current_page === 'about' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'; ?> block pl-3 pr-4 py-2 border-l-4 <?php echo $current_page === 'about' ? 'border-primary' : 'border-transparent'; ?> text-base font-medium">Market Insights</a>
                <a href="contact.php" class="<?php echo $current_page === 'contact' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-primary'; ?> block pl-3 pr-4 py-2 border-l-4 <?php echo $current_page === 'contact' ? 'border-primary' : 'border-transparent'; ?> text-base font-medium">Contact</a>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <a href="add_property.php" class="w-full bg-primary text-white px-4 py-2 rounded-md text-base font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">List Your Property</a>
                </div>
            </div>
        </div>
    </header>

    <main class="pt-16"> <!-- Added padding-top to create space below the navbar -->
        <!-- Main content will be inserted here -->
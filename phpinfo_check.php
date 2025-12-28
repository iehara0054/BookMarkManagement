<?php
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Thread Safety: " . (PHP_ZTS ? "enabled" : "disabled") . "\n";
echo "Architecture: " . (PHP_INT_SIZE === 8 ? "x64" : "x86") . "\n";
echo "Compiler: " . (defined('PHP_COMPILER_ID') ? PHP_COMPILER_ID : 'N/A') . "\n";
echo "\n";
echo "Xdebug installed: " . (extension_loaded('xdebug') ? "Yes - Version " . phpversion('xdebug') : "No") . "\n";

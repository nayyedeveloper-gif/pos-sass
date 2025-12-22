<?php

// Machine ID provided by the user
$machineId = '6cf04897cd712767c54ca2af5858553c';

/**
 * Generate a License Key from a Machine ID
 * 
 * @param string $machineId The unique machine identifier
 * @return string The formatted license key
 */
function generateLicenseKey(string $machineId): string
{
    // 1. Create a unique hash from the machine ID
    // We use SHA-256 for a good balance of length and uniqueness
    $hash = hash('sha256', $machineId);

    // 2. Take the first 25 characters of the hash for the key
    $keyPart = strtoupper(substr($hash, 0, 25));

    // 3. Format the key into groups of 5 for readability (e.g., XXXXX-XXXXX-XXXXX-XXXXX-XXXXX)
    $formattedKey = implode('-', str_split($keyPart, 5));

    return $formattedKey;
}

// Generate the license key
$licenseKey = generateLicenseKey($machineId);

// Output the result
echo "Machine ID: " . $machineId . "\n";
echo "Generated License Key: " . $licenseKey . "\n";

?>

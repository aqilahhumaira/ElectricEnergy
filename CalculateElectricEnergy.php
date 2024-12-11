<?php
// Function to calculate power, energy, and total charge
function calculate_charge($voltage, $current, $rate, $hours) {
    // Calculate Power in Watt-hour (Wh)
    $power = $voltage * $current; // Power in Watt (W)
    
    // Convert Power to kW by dividing by 1000
    $power_kw = $power / 1000; // Power in Kilowatts (kW)
    
    // Calculate Energy in kWh
    $energy = ($power_kw * $hours); // Energy in kWh
    
    // Calculate Total Charge (in RM)
    $total_charge = $energy * ($rate / 100); // Total charge in RM
    
    return [
        'power' => $power_kw,  // Power in kW
        'energy' => $energy,
        'total_charge' => $total_charge
    ];
}

$power = $energy = $total_charge = 0;
$hours_list = range(1, 24); // For hours 1 to 24

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $voltage = $_POST['voltage'];
    $current = $_POST['current'];
    $rate = $_POST['rate'];
    
    $results = [];
    foreach ($hours_list as $hour) {
        // Call the function to calculate the values for each hour
        $result = calculate_charge($voltage, $current, $rate, $hour);
        $results[] = [
            'hour' => $hour,
            'energy' => $result['energy'],
            'total_charge' => $result['total_charge']
        ];
    }
    
    // Calculate Power and Energy for 1 hour for display
    $power = $voltage * $current;
    $power_kw = $power / 1000; // Power in Kilowatts (kW)
    $energy = ($power_kw * 1); // Energy for 1 hour
    $total_charge = $energy * ($rate / 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Calculate</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="voltage">Voltage</label>
            <input type="number" class="form-control" id="voltage" name="voltage" step="0.01" placeholder="Enter Voltage (V)" required value="<?php echo isset($voltage) ? $voltage : ''; ?>">
            <label for="voltage">Voltage (V)</label>
        </div>
        <div class="form-group">
            <label for="current">Current</label>
            <input type="number" class="form-control" id="current" name="current" step="0.01" placeholder="Enter Current (A)" required value="<?php echo isset($current) ? $current : ''; ?>">
            <label for="current">Ampere (A)</label>
        </div>
        <div class="form-group">
            <label for="rate">Current Rate</label>
            <input type="number" class="form-control" id="rate" name="rate" step="0.01" placeholder="Enter Rate (sen/kWh)" required value="<?php echo isset($rate) ? $rate : ''; ?>">
            <label for="rate">sen/kWh</label>
        </div>
        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="mt-4">
            <h4>Results:</h4>
            <p><strong>Power:</strong> <?php echo number_format($power_kw, 5); ?>kW</p>
            <p><strong>Rate:</strong> <?php echo number_format($rate / 100, 3); ?>RM</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Hour</th>
                        <th>Energy (kWh)</th>
                        <th>Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $index => $result): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $result['hour']; ?></td>
                        <td><?php echo number_format($result['energy'], 5); ?></td>
                        <td><?php echo number_format($result['total_charge'], 2); ?></td> <!-- Round to 2 decimal places -->
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>

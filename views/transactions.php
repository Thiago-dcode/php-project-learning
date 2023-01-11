

<!DOCTYPE html>
<html>
    <head>
        <title>Transactions</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }
        </style>
    </head>
    <body>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

            <?php 
              
              $index = dirname(__DIR__) . DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'index.php';
              require_once $index;
                $body = $appData[0];
                $foot = $appData[1];
                
                foreach ($body as  $value) {
                    echo $value;
                }


              
            ?>
            
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td><?php echo $foot['income'] ?></td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td><?php echo  $foot['expense'] ?></td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td><?php echo $foot['total'] ?></td>
                </tr>
            </tfoot>
        </table>
    </body>
</html>

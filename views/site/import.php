<?php

/* @var $this yii\base\Widget */
/* @var $imports app\models\Import */


$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Imports!</h1>

    </div>

    <div class="body-content">

        <div class="row">
                <table style="width:100%">
                    <tr>
                        <th>N</th>
                        <th>Status</th>
                        <th>Store Name</th>
                        <th>Products Import</th>
                        <th>Failed Imports</th>
                    </tr>
                    <?php foreach ($imports as $import) { ?>
                        <tr>
                            <td><?php echo $import->id ?></td>
                            <td><?php echo $import->status ?></td>
                            <td><?php echo $import->store->title ?></td>
                            <td><?php echo $import->storeProductsCount ?></td>
                            <td><?php echo $import->failed ?></td>
                        </tr>
                    <?php } ?>
                </table>

        </div>

    </div>
</div>

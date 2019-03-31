<table cellpadding="3" cellspacing="0" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Пароль</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rs as $item):?>
        <tr>
            <td><?php echo $item['id'];?></td>
            <td><?php echo $item['name'];?></td>
            <td><?php echo $item['auth_key'];?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
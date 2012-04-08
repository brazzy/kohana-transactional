<html>
<head>
<title>Kohana Transactional Module Test</title>
<style type="text/css">
td.ok{
	background-color: green;
}
td.fail{
	background-color: red;
}
</style>
</head>
<body>
<h1>Kohana Transactional Module Test Results</h1>
<table>
<tr>
<th>Controller</th>
<th>Action</th>
<th>URL</th>
<th>Response Code</th>
<th>DB changed</th>
</tr>
<?php foreach($tests as $test) { ?>
<tr>
<td><?php echo $test->controller ?></td>
<td><?php echo $test->action ?></td>
<td><a href="http://<?php echo $test->url ?>"><?php echo $test->url ?></a></td>
<td class="<?php echo $test->responseCode_status ?>"><?php echo $test->responseCode ?></td>
<td class="<?php echo $test->dbChange_status ?>"><?php echo $test->dbChange ?></td>
</tr>
<?php } ?>
</table>


</body>
</html>
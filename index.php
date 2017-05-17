<?php
$ds = DIRECTORY_SEPARATOR;
$history_file = dirname(__FILE__).$ds.'history';
$error = '';

if (!is_writable($history_file)) {
	$error = 'History file is not writable. Your written code will not be saved.';
}

$value = '';
if (file_exists($history_file)) {
	$value = file_get_contents($history_file);
}

$template_dir = dirname(__FILE__).$ds.'templates'.$ds;
$files = scandir($template_dir);
$templates = array();
$i = 1;

foreach ($files as $file) {
	if (in_array($file, array('.', '..','.gitkeep'))) {
		continue;
	}
	
	$data = file_get_contents($template_dir.$file);
	$templates[$i] = array(
		'caption' => pathinfo($file, PATHINFO_FILENAME),
		'code' => $data
	);
	$i++;
}

?><!DOCTYPE html>
<html>
<head>
	<title>PHP console</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="styles/style.css" type="text/css"/>
	<link rel="stylesheet" href="node_modules/codemirror/lib/codemirror.css">
	<link rel="stylesheet" href="node_modules/codemirror/theme/ambiance.css">
	<link rel="stylesheet" href="node_modules/codemirror/addon/scroll/simplescrollbars.css">
	<link href="//fonts.googleapis.com/css?family=Roboto&amp;subset=cyrillic" rel="stylesheet">
	<script src="node_modules/codemirror/lib/codemirror.js"></script>
	<script src="node_modules/codemirror/addon/edit/matchbrackets.js"></script>
	<script src="node_modules/codemirror/addon/scroll/simplescrollbars.js"></script>
	<script src="node_modules/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="node_modules/codemirror/mode/xml/xml.js"></script>
	<script src="node_modules/codemirror/mode/javascript/javascript.js"></script>
	<script src="node_modules/codemirror/mode/css/css.js"></script>
	<script src="node_modules/codemirror/mode/clike/clike.js"></script>
	<script src="node_modules/codemirror/mode/php/php.js"></script>
	<script src="node_modules/jquery/dist/jquery.min.js"></script>
	<script src="node_modules/jquery.hotkeys/jquery.hotkeys.js"></script>
	<script src="scripts/main.js"></script>
	<link rel="icon" href="favicon.ico?v=1.1"> 
</head>
<body>
	<div class="ide">
		<header>
			<div class="ide__toolbar">
				<div class="ide__loading hidden" id="loading">
					<div class="sk-fading-circle">
						<div class="sk-circle1 sk-circle"></div>
						<div class="sk-circle2 sk-circle"></div>
						<div class="sk-circle3 sk-circle"></div>
						<div class="sk-circle4 sk-circle"></div>
						<div class="sk-circle5 sk-circle"></div>
						<div class="sk-circle6 sk-circle"></div>
						<div class="sk-circle7 sk-circle"></div>
						<div class="sk-circle8 sk-circle"></div>
						<div class="sk-circle9 sk-circle"></div>
						<div class="sk-circle10 sk-circle"></div>
						<div class="sk-circle11 sk-circle"></div>
						<div class="sk-circle12 sk-circle"></div>
					</div>
				</div>
				<ul>
					<li>
						<button type="button" onclick="phpSandbox.execute();" title="Press F9 shortcut to execute from keyboard" class="primary">Execute</button>
						<button type="button" onclick="phpSandbox.clear();">Clear</button>
					</li>
					<li>
						<label>
							<input type="checkbox" checked id="auto" /> Auto update
						</label>
					</li>
					<li>
						<label class="form-label">Template:</label>
						<div class="select-style">
							<select id="template-selector" onchange="phpSandbox.setTemplate(this.value)">
								<optgroup label="Templates">
									<?php
									if (!empty($templates)) {
										?>
										<option value="0"></option>
										<?php
										foreach ($templates as $index => $template) {
											?>
											<option value="<?=$index?>"><?=$template['caption']?></option>
											<?php
										}
									} else {
										?>
										<option disabled="disabled">No templates found.</option>
										<?php
									}
									?>
								</optgroup>
							</select>
						</div>
					</li>
					<?php
					if ($error) {
						echo '<li class="error">'.$error.'</li>';
					}
					?>
				</ul>
			</div>
		</header>
		<main>
			<div class="code">
				<div class="code__source" id="code">
					
				</div>
				<div class="code__result">
					<iframe id="results"></iframe>
				</div>
			</div>
		</main>
	</div>
	
	<script>
		$(function(){
			phpSandbox.value = <?=json_encode($value)?>;
			phpSandbox.templates = <?=json_encode($templates)?>;
			phpSandbox.init();
		});
	</script>
</body>
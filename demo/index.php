<?php 
if (isset($_REQUEST['ajax']) ){
	header('Content-Type: application/json');
	if ( isset( $_REQUEST['data'] ) ){
		$data = json_decode( stripslashes( $_REQUEST['data'] ) );
	} else {
		$data = array(
			array('Carbon','Hydrogen','Nitrogen','Oxygen'),
			array(10,15,1,0),
			array(8,11,1,2),
			array(10,15,1,1),
			array(12,17,1,1),
			array(14,19,1,2)
		);
	}
	echo json_encode( $data );
	die();
}
?><!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        
        <title>jQuery editTable</title>
        <meta name="description" content="jQuery editTable is a very small jQuery Plugin (~1Kb gzipped) that fill the gap left by the missing of a default input field for data tables.">
        <link rel="stylesheet" href="main.css?v=0.2.0">
        <script src="//code.jquery.com/jquery-latest.js"></script>
        <script type="text/javascript" src="../jquery.edittable.js?v=0.2.0"></script>
        <script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <link rel="stylesheet" href="../jquery.edittable.css?v=0.2.0">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
        <script>
        $(window).ready(function () {
        	
        	// Initialize table example 0
        	$('#source').editTable();
        	
        	// Initialize table example 1
	        var eTable = $('#edittable').editTable({
	        	data : [
	        		["Click on the plus symbols on the top and right to add cols or rows"]
	        	]
	        });
	        
	        // Load json data trough an ajax call
	        $('.loadjson').click(function () {
	        	var _this = $(this),text = $(this).text();
	        	$(this).text('Loading...');
	        	$.ajax({
	        		url: 	'index.php',
	        		type: 	'POST',
	        		data: 	{
	        			ajax: true
	        		},
	        		complete: function (result) {
	        			_this.text(text);
	        			eTable.loadJsonData(result.responseText);
	        		}
	        	});
	        	return false;
	        });
	        
	        // Send JSON data trough an ajax call
	        $('.sendjson').click(function () {
	        	$.ajax({
	        		url: 	'index.php',
	        		type: 	'POST',
	        		data: 	{
	        			ajax: true,
	        			data: eTable.getJsonData()
	        		},
	        		complete: function (result) {
	        			console.log('Server response', JSON.parse(result.responseText));
	        		}
	        	});
	        	return false;
	        });
	        
	        // Reset table data
	        $('.reset').click(function () {
	        	eTable.reset();
	        	return false;
	        });
	        
	        // Initialize table example 2
	        $("#edittable2").editTable({
	        	data : [
	        		["01/01/2013","01/30/2013","50,00 €"],
	        		["02/01/2013","02/28/2013","50,00 €"]
	        	],
	        	headerCols: [
	        		'From',
	        		'To',
	        		'Price'
	        	],
	        	first_row: false,
	        	maxRows: 3
	        });
	        
	        // Example of jQuery UI 
        	$("#edittable2").on("focusin", "td:nth-child(1) input, td:nth-child(2) input", function(){
        	    $(this).datepicker();
        	});

        	// Example 4 EDITED by shadow7853        
        	// shadow7853: I've modified the code to enable headerCols, row_templates to be also partials (define templates for first cells, others will be 'text') and to not disable column adding.
        	// shadow7853: Also data rows could be partials, not all with the same length.
        	// Custom fields & validation
            var mynewtable = $('#examplex').editTable({
                field_templates: {
                    'checkbox' : {
                        html: '<input type="checkbox"/>',
                        getValue: function (input) {
                            return $(input).is(':checked');
                        },
                        setValue: function (input, value) {
                            if ( value ){
                                return $(input).attr('checked', true);
                            }
                            return $(input).removeAttr('checked');
                        }
                    },
                    'textarea' : {
                        html: '<textarea/>',
                        getValue: function (input) {
                            return $(input).val();
                        },
                        setValue: function (input, value) {
                            return $(input).text(value);
                        }
                    },
                    'select' : {
                        html: '<select><option value="">None</option><option>All</option></select>',
                        getValue: function (input) {
                            return $(input).val();
                        },
                        setValue: function (input, value) {
                            var select = $(input);
                            select.find('option').filter(function() {
                                return $(this).val() == value; 
                            }).attr('selected', true);
                            return select;
                        }
                    }
                },
                row_template: ['checkbox', 'text', 'text', 'textarea', 'select'],
                headerCols: ['Yes/No','Date','Value','Description', 'Which?'],
                first_row: false,
                data: [
                    [false,"01/30/2013","50,00 €","Lorem ipsum...\n\nDonec in dui nisl. Nam ac libero eget magna iaculis faucibus eu non arcu. Proin sed diam ut nisl scelerisque fermentum."],
                    [true,"02/28/2013","50,00 €",'This is a <textarea>','All', 'extra-dynamic-cell'],
                    [true,"partial row"]
                ],
                validate_field: function (col_id, value, col_type, $element) {
                    if ( col_type === 'checkbox' ) {
                        $element.parent('td').animate({'background-color':'#fff'});
                        if ( value === false ){
                            $element.parent('td').animate({'background-color':'#DB4A39'});
                            return false;
                        }
                    }
                    return true;
                },
                tableClass: 'inputtable custom'
            });

            $('#examplexconsole').click(function(e) {
                console.log(mynewtable.getData());
                if ( !mynewtable.isValidated() ){
                    alert('Not validated');
                }
                e.preventDefault();
            });
        	
        	$('.showcode').click(function () {
        		$($(this).attr('href')).slideToggle(300);
        		return false;
        	});
        	
        });
        </script>
    </head>

<body>

  <div class="container">
                 
    <h1>jQuery editTable <span>v0.2.0</span></h1>
    
    <a href="https://twitter.com/micc1983" class="twitter-follow-button" data-show-count="true" data-lang="en">Follow @micc1983</a>
    <a href="https://twitter.com/share" class="twitter-share-button" data-via="Micc1983">Tweet</a>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                         
    <p>jQuery editTable is a very small jQuery Plugin (~1Kb gzipped) that fill the gap left by the missing of a default <strong>input field for data tables</strong>. jQuery editTable can be used both in ajax and/or HTTP POST contest and let you preset the title and number of columns or just let complete freedom to the user. You can even append custom behaviors to single column cells (ex. <strong>jQuery UI Datepicker</strong>). The only limit is your imagination! :)</p>
                         
    <a href="https://github.com/micc83/editTable" class="download_button">Download it on GitHub</a>
	
	<p>To use it you just have to include jQuery and a copy of the plugin in your head or footer:</p>
<pre>
&#x3C;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x20;&#x74;&#x79;&#x70;&#x65;&#x3D;&#x22;&#x74;&#x65;&#x78;&#x74;&#x2F;&#x6A;&#x61;&#x76;&#x61;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x22;&#x20;&#x73;&#x72;&#x63;&#x3D;&#x22;&#x68;&#x74;&#x74;&#x70;&#x3A;&#x2F;&#x2F;&#x63;&#x6F;&#x64;&#x65;&#x2E;&#x6A;&#x71;&#x75;&#x65;&#x72;&#x79;&#x2E;&#x63;&#x6F;&#x6D;&#x2F;&#x6A;&#x71;&#x75;&#x65;&#x72;&#x79;&#x2D;&#x6C;&#x61;&#x74;&#x65;&#x73;&#x74;&#x2E;&#x6A;&#x73;&#x22;&#x3E;&#x3C;&#x2F;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3E;
&#x3C;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x20;&#x74;&#x79;&#x70;&#x65;&#x3D;&#x22;&#x74;&#x65;&#x78;&#x74;&#x2F;&#x6A;&#x61;&#x76;&#x61;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x22;&#x20;&#x73;&#x72;&#x63;&#x3D;&#x22;&#x6A;&#x71;&#x75;&#x65;&#x72;&#x79;&#x2E;&#x65;&#x64;&#x69;&#x74;&#x74;&#x61;&#x62;&#x6C;&#x65;&#x2E;&#x6D;&#x69;&#x6E;&#x2E;&#x6A;&#x73;&#x22;&#x3E;&#x3C;&#x2F;&#x73;&#x63;&#x72;&#x69;&#x70;&#x74;&#x3E;
&#x3C;&#x6C;&#x69;&#x6E;&#x6B;&#x20;&#x72;&#x65;&#x6C;&#x3D;&#x22;&#x73;&#x74;&#x79;&#x6C;&#x65;&#x73;&#x68;&#x65;&#x65;&#x74;&#x22;&#x20;&#x68;&#x72;&#x65;&#x66;&#x3D;&#x22;&#x6A;&#x71;&#x75;&#x65;&#x72;&#x79;&#x2E;&#x65;&#x64;&#x69;&#x74;&#x74;&#x61;&#x62;&#x6C;&#x65;&#x2E;&#x6D;&#x69;&#x6E;&#x2E;&#x63;&#x73;&#x73;&#x22;&#x3E;
</pre>
	
	<p>Now you can trigger editTable on any textarea or block element (ex. div, article, section ...). In case you trigger it on a textarea, its content will be used as JSON source for the table. If the textarea is inside a form, on submit, its content will be updated with the new JSON data. Otherwise, if you trigger it on a block element the table will be appended to the element itself (ajax).</p>
	
	<pre>
var mytable = $('#edittable').editTable({
    data: [['']],           // Fill the table with a js array (this is overridden by the textarea content if not empty)
    tableClass: 'inputtable',   // Table class, for styling
    jsonData: false,        // Fill the table with json data (this will override data property)
    headerCols: false,      // Fix columns number and names (array of column names)
    maxRows: 999,           // Max number of rows which can be added
    first_row: true,        // First row should be highlighted?
    row_template: false,    // An array of column types set in field_templates
    field_templates: false, // An array of custom field type objects
    fixed_rows: false,      // Hide add/remove row buttons
    fixed_cols: false,      // Hide add/remove column buttons

    // Validate fields
    validate_field: function (col_id, value, col_type, $element) {
        return true;
    }
});
</pre>
	
	<p>There are of course many methods which can be used on the created table. Let's see...</p>
	
	<pre>
mytable.loadData(dataArray);    // Fill the table with js data
mytable.loadJsonData(jsonData); // Fill the table with JSON data
mytable.getData();              // Get a js array of the table data
mytable.getJsonData();          // Get JSON from the table data
mytable.reset();                // Reset the table to the initial set of data
mytable.isValidated()           // Check if the table pass validation set with validate_field
</pre>
	
	<p>To define a <strong>custom field type</strong> object (<a href="#e4">click here for a full example</a>):</p>
    <pre>
[
    'checkbox' : {
        
        html: '&lt;input type="checkbox"/&gt;',     // Input type html

        // How to get the value from the custom input
        getValue: function (input) {
            return $(input).is(':checked');
        },

        // How to set the value of the custom input
        setValue: function (input, value) {
            if ( value ){
                return $(input).attr('checked', true);
            }
            return $(input).removeAttr('checked');
        }
    }
]
</pre>

	<p>That's it, now give a look to the following examples to understand how it works.</p>
	
	<hr>
	
	<h3>Example 1 - Basics</h3>
	
	<p>In the first example we'll implement the simplest HTML POST use of editTable. If you are looking to use editTable on ajax contest instead just give a look to the next example.</p>
	
	<form method="post" action="#output">
		<textarea id="source" style="display: none;" name="myField"><?php 
		echo json_encode(array(
			array('Period','Full Board', 'Half Board', 'Bed & Breakfast'),
			array('01/01 - 31/01','50.00 €', '40.00 €', '30.00 €'),
			array('01/02 - 28/02','55.00 €', '45.00 €', '35.00 €'),
			array('01/03 - 31/03','60.00 €', '50.00 €', '40.00 €'),
			array('01/04 - 30/04','55.00 €', '45.00 €', '35.00 €'),
			array('01/05 - 31/05','50.00 €', '40.00 €', '30.00 €')
		)); 
		?></textarea>
		<button type="submit">Post data (will reload the page)</button> 
		<a href="#example0code" class="showcode button">Show Code</a>
	</form>
	
	<div id="example0code" style="display: none;" class="examplecode">
	<span class="title">INDEX.PHP</span>
<pre>
&lt;form method=&quot;post&quot; action=&quot;output.php&quot;&gt;
	&lt;textarea id=&quot;source&quot; style="display:none" name="myField" &gt;&lt;?php 
	echo json_encode(array(
		array('Period','Full Board', 'Half Board', 'Bed &amp; Breakfast'),
		array('01/01 - 30/01','50.00 €', '40.00 €', '30.00 €'),
		array('01/02 - 28/02','55.00 €', '45.00 €', '35.00 €'),
		array('01/03 - 31/03','60.00 €', '50.00 €', '40.00 €'),
		array('01/04 - 30/04','55.00 €', '45.00 €', '35.00 €'),
		array('01/05 - 31/05','50.00 €', '40.00 €', '30.00 €')
	)); 
	?&gt;&lt;/textarea&gt;
	&lt;button type=&quot;submit&quot;&gt;Send data&lt;/button&gt;
&lt;/form&gt;
</pre>
	
	<span class="title">SCRIPT.JS</span>
	<pre>
$(window).ready(function () {
	$('#source').editTable();
});
</pre>

	<span class="title">OUTPUT.PHP</span>
<pre>
&#x3C;&#x3F;&#x70;&#x68;&#x70; var_dump( json_decode( stripslashes( $_POST['myField'] ) ) ); &#x3F;&#x3E;
</pre>
	
	</div>
	
	<!-- Example output -->
	<span class="title" id="output">Post Output</span>
	<pre><?php var_dump( json_decode( stripslashes( $_POST['myField'] ) ) ); ?></pre>
	
	<hr>
	
	<h3>Example 2 - AJAX with editable columns:</h3>
	
	<div id="edittable"></div>
	<a href="#" class="sendjson button">Ajax send JSON (check your console)</a> 
	<a href="#" class="loadjson button">Ajax load JSON</a> 
	<a href="#" class="reset button">Reset Table</a>
	<a href="#example1code" class="showcode button">Show Code</a>

	<div id="example1code" style="display: none;" class="examplecode">
		<span class="title">index.htm</span>
<pre>
&lt;div id=&quot;edittable&quot;&gt;&lt;/div&gt;
&lt;a href=&quot;#&quot; class=&quot;sendjson button&quot;&gt;Send JSON (check your console)&lt;/a&gt; 
&lt;a href=&quot;#&quot; class=&quot;loadjson button&quot;&gt;Load JSON from textarea&lt;/a&gt; 
&lt;a href=&quot;#&quot; class=&quot;reset button&quot;&gt;Reset Table&lt;/a&gt;
</pre>	
		<span class="title">script.js</span>
<pre>
// Initialize table example 1
var eTable = $('#edittable').editTable({
	data: [
		["Click on the plus symbols on the top and right to add cols or rows"]
	]
});

// Load json data trough an ajax call
$('.loadjson').click(function () {
	var _this = $(this),text = $(this).text();
	$(this).text('Loading...');
	$.ajax({
		url: 	'output.php',
		type: 	'POST',
		data: 	{
			ajax: true
		},
		complete: function (result) {
			_this.text(text);
			eTable.loadJsonData(result.responseText);
		}
	});
	return false;
});

// Reset table data
$('.reset').click(function () {
	eTable.reset();
	return false;
});

// Send JSON data trough an ajax call
$('.sendjson').click(function () {
	$.ajax({
		url: 	'output.php',
		type: 	'POST',
		data: 	{
			ajax: true,
			data: eTable.getJsonData()
		},
		complete: function (result) {
			console.log(JSON.parse(result.responseText));
		}
	});
	return false;
});
</pre>
<span class="title">output.php</span>
<pre>
&lt;?php 
if ( isset( $_POST['ajax'] ) ){
	header( 'Content-Type: application/json' );
	if ( isset( $_REQUEST['data'] ) ){
		$data = json_decode( stripslashes( $_REQUEST['data'] ) );
	} else {
		$data = array(
			array('Carbon','Hydrogen','Nitrogen','Oxygen'),
			array(10,15,1,0),
			array(8,11,1,2),
			array(10,15,1,1),
			array(12,17,1,1),
			array(14,19,1,2)
		);
	}
	echo json_encode( $data );
	die();
}
</pre>
	
	</div>

	<hr>
	
    <h3>Example 3 - Fixed columns, Datepicker, Rows limit:</h3>
    
    <div id="edittable2"></div>
    <a href="#example2code" class="showcode button">Show Code</a>
    
    <div id="example2code" style="display: none;" class="examplecode">

<span class="title">index.htm</span>
<pre>&lt;div id=&quot;edittable2&quot;&gt;&lt;/div&gt;</pre>

<span class="title">script.js</span>
<pre>
// Initialize table example 3
$("#edittable2").editTable({
	data: [
		["01/01/2013","01/30/2013","50,00 €"],
		["02/01/2013","02/28/2013","50,00 €"]
	],
	headerCols: [
		'From',
		'To',
		'Price'
	],
	maxRows: 3
});

// Example of jQuery UI datePicker
$("#edittable2").on("focusin", "td:nth-child(1) input, td:nth-child(2) input", function(){
    $(this).datepicker();
});</pre>
    </div>
    
    <hr>

    <h3 id="e4">Example 4 - Custom field types &amp; validation</h3>
    <form method="post" action="#output">
        <textarea id="examplex" style="display: none;" name="myField"></textarea>
        <a href="#examplexcode" class="showcode button">Show Code</a>
        <a href="#" id="examplexconsole" class="button">Validate table</a>
    </form>

    <div id="examplexcode" style="display: none;" class="examplecode">

        <span class="title">script.js</span>
<pre>
/**
 * Example 4 - Custom field types & validation
 */
var mynewtable = $('#examplex').editTable({
    field_templates: {
        'checkbox' : {
            html: '&lt;input type="checkbox"/&gt;',
            getValue: function (input) {
                return $(input).is(':checked');
            },
            setValue: function (input, value) {
                if ( value ){
                    return $(input).attr('checked', true);
                }
                return $(input).removeAttr('checked');
            }
        },
        'textarea' : {
            html: '&lt;textarea/&gt;',
            getValue: function (input) {
                return $(input).val();
            },
            setValue: function (input, value) {
                return $(input).text(value);
            }
        },
        'select' : {
            html: '&lt;select&gt;&lt;option value=""&gt;None&lt;/option&gt;&lt;option&gt;All&lt;/option&gt;&lt;/select&gt;',
            getValue: function (input) {
                return $(input).val();
            },
            setValue: function (input, value) {
                var select = $(input);
                select.find('option').filter(function() {
                    return $(this).val() == value; 
                }).attr('selected', true);
                return select;
            }
        }
    },
    row_template: ['checkbox', 'text', 'text', 'textarea', 'select'],
    headerCols: ['Yes/No','Date','Value','Description', 'Which?'],
    first_row: false,
    data: [
        [false,"01/30/2013","50,00 €","Lorem ipsum...\n\nDonec in dui nisl. Nam ac libero eget magna iaculis faucibus eu non arcu. Proin sed diam ut nisl scelerisque fermentum."],
        [true,"02/28/2013","50,00 €",'This is a &lt;textarea&gt;','All']
    ],

    // Checkbox validation
    validate_field: function (col_id, value, col_type, $element) {
        if ( col_type === 'checkbox' ) {
            $element.parent('td').animate({'background-color':'#fff'});
            if ( value === false ){
                $element.parent('td').animate({'background-color':'#DB4A39'});
                return false;
            }
        }
        return true;
    },
    tableClass: 'inputtable custom'
});

// Trigger event
$('#examplexconsole').click(function(e) {

    // Get data
    console.log(mynewtable.getData());

    // Check if data are valid
    if ( !mynewtable.isValidated() ){
        alert('Not validated');
    }

    e.preventDefault();
});
</pre>

    </div>

    <hr>
    
    <h2>Credits and contacts</h2>
                            
	<p><strong>editTable</strong> has been made by <a href="http://codeb.it" target="_blank">me</a>. You can contact me at <a href="mailto:micc83@gmail.com">micc83@gmail.com</a> or <a href="https://twitter.com/Micc1983" target="_blank">twitter</a> for any issue or feauture request.</p>
    
  </div> 
  
</body>
</html>
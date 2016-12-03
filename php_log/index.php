
<!DOCTYPE html>
<html style="background-color: #1F1F1F">
<head>
    <style>
		body{
			margin: 0px;
			padding: 0px;
			color: white;
			font-family:Verdana;
			font-size: 12px;
		}
		table{
			border-top:1px solid grey;
			width:100%;
		}
		td{
			padding: 5px;
			margin: 0px;
			border-bottom:1px solid grey;
		}
		tr{
			transition: all 0.5s;
		}
		tr:hover{
            //transition: all 0s;
			//background-color:#3D3D3D;
		}
		
		.button{
			-webkit-touch-callout: none; /* iOS Safari */
			-webkit-user-select: none;   /* Chrome/Safari/Opera */
			-khtml-user-select: none;    /* Konqueror */
			-moz-user-select: none;      /* Firefox */
			-ms-user-select: none;       /* IE/Edge */
			user-select: none;           /* non-prefixed version, currently*/
			cursor: default;
			display:inline-block;
			margin: 5px;
			padding:8px;
			border:1px solid grey;
		}
		
		.button:hover{
			background-color: grey;
		}
		
		.new_log{
			background-color: grey;
		}
		
    </style>
</head>
<body>
	<span class='button' onclick='clear_log()'>Clear Log</span>
	<span class='button' onclick='test_error()'>Test Error</span>
	<h2 style="text-align:center; padding:0px margin:0px; display:inline-block">CS PHP Log</h2>
	
	</br>
	<table id="errors"></table>
    <script type="text/javascript">
		
        //Check and reset
		check(1);
		function check(reset){
			/*--------------------------------------------------------
			| Desc: Sends Ajax call to server
			| Ex: csdb_func('new', update, 1, 2);
			--------------------------------------------------------*/
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (xhttp.readyState == 4 && xhttp.status == 200) {
					info = JSON.parse(xhttp.responseText);
					new_errors = JSON.parse(info.errors);
					if(new_errors[0] != ""){
						for(i = 0; i < new_errors.length; i++){
							console.log(new_errors[i]);
							tr = document.createElement('tr');
							tr.innerHTML = '<td>' + new_errors[i] + '</td>';
							tr.classList = 'new_log';
							setTimeout(fade, 100, tr);
							appendFirst(tr, document.getElementById('errors'));
						}
					}
					check(0);
				}
			};
			
            gets = "";
            if(reset == 1){
                gets += "reset=1";
            }
			xhttp.open("GET", "check.php?" + gets, true);
            xhttp.send();
		}
		function appendFirst(ele, parent){
			var eles = parent.getElementsByTagName('tr');
			if(eles.length == 0){
				parent.appendChild(ele);
			} else {
				parent.insertBefore(ele, eles[0]);
			}
		}
		function fade(ele){
			ele.classList.remove('new_log');
		}
		function test_error(){
			var awdawd = new XMLHttpRequest();
			awdawd.open("GET", "error.php", true);
            awdawd.send();
		}
		
		function clear_log(){
			var xhttp = new XMLHttpRequest();
			xhttp.open("GET", "clear.php", true);
            xhttp.send();
			
			document.getElementById('errors').innerHTML = "";
		}
    </script>
</body>
</html>


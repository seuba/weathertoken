<html>
  <head></head>
  <body>
    <script src="https://albertseuba.herokuapp.com/blocksdk.js"></script>
	  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.js"></script> 
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	  <script type="text/javascript">
			(function() {
				
				var BlockSDK = [
					'blocksdk'
				];
				require(BlockSDK);
			})();
		</script>
<script>

if (window.self === window.top) {
	document.body.innerText = 'This application is for use in the Salesforce Marketing Cloud Content Builder Editor only.';
} else {
	var sdk = new SDK();
	sdk.getContent(function (content) {
		var quill = new Quill('#editor-container', {
			theme: 'snow'
		});
		quill.root.innerHTML = content;
		function saveText() {
			var html = quill.root.innerHTML;
			sdk.setContent(html);
			sdk.setSuperContent('This is super content: ' + html);

			sdk.getData(function (data) {
				var numberOfEdits = data.numberOfEdits || 0;
				sdk.setData({
					numberOfEdits: numberOfEdits + 1
				});
			});
		}
		quill.on('text-change', saveText);
	});
}

</script>
  </body>
</html>

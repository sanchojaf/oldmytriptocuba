VIGET.fileInputs = function() {
		var $this = $(this),
		$val = $this.val(),
		valArray = $val.split('\\'),
		newVal = valArray[valArray.length-1],
		$button = $this.siblings('.button'),
		$fakeFile = $this.siblings('.file-holder');
		if(newVal !== '') {
			$button.text('Photo Chosen');
			if($fakeFile.length === 0) {
				$button.after('' + newVal + '');
			} else {
				$fakeFile.text(newVal);
			}
		}
    };
     
    $(document).ready(function() {
    	$('.file-wrapper input[type=file]').bind('change focus click', VIGET.fileInputs);
    });
var Help={
	htmlspecialchars:function (str) {
	  if (typeof str === 'string') {
	    str = str.replace(/&/g, '&amp;');
	    str = str.replace(/</g, '&lt;');
	    str = str.replace(/>/g, '&gt;');
	    str = str.replace(/"/g, '&quot;');
	    str = str.replace(/'/g, '&#039;');
	  }
	  return str;
	}
}
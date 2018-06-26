// 代码整理：懒人之家
var YHover = function(){
	var D = document, ua = navigator.userAgent.toLowerCase(), isOpera = (ua.indexOf('opera') > -1), isIE = (!isOpera && ua.indexOf('msie') > -1);

	return {
		photos: function(container, samples){
			var Container = D.getElementById(container), Samples = Container.getElementsByTagName(samples), i, len = Samples.length, Overlayer = null, oSelf = this;
			for (i = 0; i < len; ++i) {
				Samples[i].onmouseover = function(){
					var imgPath = this.parentNode.href, imgAlt = this.alt, bigImg = '<img src="' + imgPath + '" alt="' + imgAlt + '" />', pageX = oSelf.getPageX(this), pageY = oSelf.getPageY(this), viewportWidth = oSelf.getViewportWidth(), viewportHeight = oSelf.getViewportHeight(), layerWidth = 0, layerHeight = 0, xScroll = oSelf.getXScroll(), yScroll = oSelf.getYScroll();
					if (!Overlayer) {
						Overlayer = D.createElement('div');
						Overlayer.id = 'overlayer';
						D.body.appendChild(Overlayer);
					}
					else {
						Overlayer.style.display = 'block';
					}
					layerWidth = Overlayer.offsetWidth;
					layerHeight = Overlayer.offsetHeight;
					if ((pageX + this.offsetWidth + 5 + layerWidth) > (viewportWidth + xScroll)) {
						pageX -= 5 + layerWidth;
					}
					else {
						pageX += this.offsetWidth + 5;
					}
					if ((pageY + this.offsetHeight + 5 + layerHeight) > (viewportHeight + yScroll)) {
						pageY -= 5 + layerHeight;
					}
					Overlayer.style.left = pageX + 'px';
					Overlayer.style.top = pageY + 'px';
					Overlayer.innerHTML = bigImg;
				};
				Samples[i].onmouseout = function(){
					Overlayer.style.display = 'none';
					Overlayer.innerHTML = '';
				};
			}
		},
		getXScroll: function(){
			var j = self.pageXOffset || D.documentElement.scrollLeft || D.body.scrollLeft;
			return j;
		},
		getYScroll: function(){
			var j = self.pageYOffset || D.documentElement.scrollTop || D.body.scrollTop;
			return j;
		},
		getViewportWidth: function(){
			var j = self.innerWidth;
			var k = D.compatMode;
			if (k || isIE) {
				j = (k == "CSS1Compat") ? D.documentElement.clientWidth : D.body.clientWidth;
			}
			return j;
		},
		getViewportHeight: function(){
			var j = self.innerHeight;
			var k = D.compatMode;
			if ((k || isIE) && !isOpera) {
				j = (k == "CSS1Compat") ? D.documentElement.clientHeight : D.body.clientHeight;
			}
			return j;
		},
		getPageX: function(el){
			var box = null, parentNode = null, left = 0;
			if (el.getBoundingClientRect) {
				box = el.getBoundingClientRect();
				left = box.left + Math.max(D.documentElement.scrollLeft, D.body.scrollLeft);
			}
			else {
				left = el.offsetLeft;
				parentNode = el.offsetParent;
				if (parentNode != el) {
					while (parentNode) {
						left += parentNode.offsetLeft;
						parentNode = parentNode.offsetParent;
					}
				}
			}
			return left;
		},
		getPageY: function(el){
			var box = null, parentNode = null, top = 0;
			if (el.getBoundingClientRect) {
				box = el.getBoundingClientRect();
				top = box.top + Math.max(D.documentElement.scrollTop, D.body.scrollTop);
			}
			else {
				alert('x');
				top = el.offsetTop;
				parentNode = el.offsetParent;
				if (parentNode != el) {
					while (parentNode) {
						top += parentNode.offsetTop;
						parentNode = parentNode.offsetParent;
					}
				}
			}
			return top;
		}
	};
}();
(function($)
{
	$.Redactor.prototype.fontsize = function()
	{
		return {
			init: function()
			{
				var fonts = [10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 25, 30];
				var that = this;
				var dropdown = {};

				$.each(fonts, function(i, s)
				{
					dropdown['s' + s] = { title: s + 'px', func: function() { that.fontsize.set(s); } };
				});

				dropdown.remove = { title: 'Tamanho Padrão', func: that.fontsize.reset };

				var button = this.button.add('fontsize', 'Alterar tamanho da fonte');
				this.button.addDropdown(button, dropdown);
			},
			set: function(size)
			{
				this.inline.format('span', 'style', 'font-size: ' + size + 'px;');
			},
			reset: function()
			{
				this.inline.removeStyleRule('font-size');
			}
		};
	};
})(jQuery);
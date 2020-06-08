jQuery(document).ready(function() {
	$.validator.addMethod("regex", function (value, element, pattern) {
		if (pattern instanceof Array) {
			for(p of pattern) {
				if (!p.test(value))
					return false;
			}
			return true;
		} else {
			return pattern.test(value);
		}
	}, "Entrez des donnes valide SVP!.");
	$(function(){
		jQuery(document).ready(function() {
			//alert("test user_validator.");
			$('.formulaire').validate({
				
				rules:{
					login:{
						
						/*remote:{
							url: "{{ path('rempli') }}",
							type:'POST',
							data:{
								nouveau_nom:function(){
									return $('#user').val();
								},
								ancien_nom:function(){
									return $('#user_old').val();
								}
							}
						},*/
						required:true,
						regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
						minlength: 3,
						maxlength: 16
					}
				},
				messages:{
					login:{
						required: 'Obligatoire', 
						regex: 'Commencer par une lettre et pas d espace SVP',
						minlength: 'Minimum 3 characters',
						maxlength: 'Maximum 16 characters',
						remote: 'Ce pseudo est deja pris'
					}
				}
			});
		});
	});
});
       
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="utf-8">
	<title><?php _e('Account Protected by 2FA via Email', 'two-factor-2fa-via-email'); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" CONTENT="noindex, nofollow">
	<link rel="icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABYCAMAAAA0hKKwAAAAflBMVEVHcEz/////////////////////////////////////////////////////////////////////////////////////////////////////AHj/C33/AHP/AG//GYb/4vD/TqL/l8n/yuT/Opf/udr/8fj/Z6//rdT/ebj/AGwXRMjbAAAAGXRSTlMA92/jE08Ht5/t2l4ekyuCN0KstMvFwNDHalugRAAABEZJREFUaN7tWtmSqjAQDatACCCICgnIJjr//4MXREeWbLg83Ko55ZN0cugl6e4EAGgwPUP1rc0Nlg+3LlVo60eOrTkd9tEmRKrhmUAS+tYPD5qdjGA7saVu9bGUC9FuItNB0Q6hb+hiBhh2Q5WEBjv2t8Oren5MF+mH2huVy6P7WsKHZhmuhxSBlG15TA7VTiSgSAkhqhuBd0w+CRtSOAwl+TCsRazB5PPYzwLASL6B40QXT/kKSRKO1+4u+RLUUewmX8PvgnHt75FEDxI/+SKCu0fsb5JYoiVyPp9OJ+E05x7slT8slpD6sLkWeYc0z4trw2Q6lW3di+G8LsqKKmIwrdXUJCNp2v1Skv0QXFyXM5ybtk6zjPRISSeW1hXLXgHlQUmydIJurry9nMcqFHmWzaVISVn2jB2lIb0KeDoDybK8uNwMV5UFJjeZGQip6E6hkNSU8XceXJRljX8yukCa1cvZXCrJCadsZAsj3fAYgpcxsqWSNNnCWJg1L549JA01vJYkFzKaBz8m4an3FMgusiRZ+jKILEkleFuKsZ7/n+gkCjW68NJAT7MxTUeKRNLxI6c8JxW6ZJCo6CmFlt7bjBNVi6gdxTdlxSs6q4YoSCrjmBkTjSM5sPaukS7TCTFHQ4IvzIRi0uvfEpPJlFhgMEIKekKAvOx7amnbJEsZUl8YVfGQtDxWRmqKlEjFFEmLRlR6RczcWT1opitupgRuK3Zy3t5JAk4Cr1pMuMYi+MorA/a/xV3IrRPKnG61QbvrSaYikqjuOudQPU5SnqF6oDVFfVNT1g2pBRTJYVLWi4vI63yTJHkp7LZmraMlZGnyqWeuwrJP2c5bLTHLuR0XDFWyngMAlEgq09mKtGdxZxpQO+xEQpmbyUgpFt3Re2xgaOKxl35tXsRyyGSds+ihePSpxmJ3aAbv4MNwJGjEaggOcXT/7ZZoH4iPolz0Vr99gHInXt7rNI4KpOG+ZjQHmmANXF9bb6h1FLcQUJ01FEcIXoIpT+O8SDHQSPnGVsFbcCX2TcsF7yLYvXAOuN5mXGUcD3wGnISmuR/iADrT/UoAPgZfpuR5F57wYO4TvmfsMQ74JBhhvPv/SFjmktx3kbXVv+l4F1oI7G+XIuaLIewLtiR1b/c9yuZ++cLTx2WmY4Wz4AP/MAht7iTDJQ+Dx+WkFcdlXIyh56ARSb+hRirlgo1fWGqQ5obJkCnJ7dUiH3qu+XsXqAovC+LRm+keRLu5cZckg6mdOLQsK4wlqwlt34tb0VGjXXkxSD6LP5I/Eul6FH2fJNZX37NtQPzCXauxrrGIwara/VEtutGKQZq6qkUY3X9D2fZF84e93YSRRP+mWMG0fZGhOYy/MnDViGtn5aguEocJN/x3c9DidEUPVBQ72nycovWbPyMB6gbaOPaCSrGdPYLMbz/0AP5+S9J/ToJUIzBFX4dAH4Wb8RgYTHPsPxIzdm/MInasAAAAAElFTkSuQmCC" />
	<style>
        body,html { padding:0; margin:0; height:100%; }
        .holder-page { background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); color:white; height:100%; position:relative; text-align:center; background-size: 400% 400%; animation: gradient 15s ease infinite; }
        .holder-page img { max-width:150px; }
        .holder-page h1, .holder-page p, .holder-page a { color:white; font-weight: 300; }
        .holder-page p { font-size:24px; }
        .holder-page .container { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width: calc(100% - 80px); padding:0 40px; }
        h1, h2, h3, h4, h5, h6, p { font-family: 'Titillium Web', sans-serif; line-height:1.3; }
        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        @media screen and (max-width:768px) {
            .holder-page p { font-size:18px; }
        }
    </style>
	<script>
		let txt_m = '<?php _e('minute', 'two-factor-2fa-via-email'); ?>';
		let txt_ms = '<?php _e('minutes', 'two-factor-2fa-via-email'); ?>';
		let txt_s = '<?php _e('second', 'two-factor-2fa-via-email'); ?>';
		let txt_ss = '<?php _e('seconds', 'two-factor-2fa-via-email'); ?>';

		document.addEventListener('DOMContentLoaded', function(){

			let timer = document.querySelector('#timer');
			let minutes, seconds = 0;

			if(timer) {

				minutes = parseInt(timer.dataset.minutes);

				setInterval(function() {

					if(seconds == 0) {
						
						minutes--;
						seconds = 60;

					}

					seconds--;

					if(minutes == 0 && seconds == 0) {

						document.querySelector('#timertext').innerHTML = '<?php _e('The link has expired. Please login again.', 'two-factor-2fa-via-email'); ?>';
						return;

					}

					let finalHTML = '';
					if(minutes) finalHTML += minutes + ' ' + ((minutes>1) ? txt_ms : txt_m);
					if(minutes && seconds) finalHTML += ', ';
					if(seconds) finalHTML += seconds + ' ' + ((seconds>1) ? txt_ss : txt_s);

					timer.innerHTML = finalHTML;

				}, 1000);

			}

			setInterval(checkUserLoggedIn, 3000);

		});

		function checkUserLoggedIn() {

			fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'action=ss882fave_is_user_logged_in'
			})
			.then(response => response.json())
			.then(data => { if(data.logged_in) window.location = '<?php echo get_site_url(); ?>'; })
			.catch(console.error);

		}
	</script>
</head>

<body>

	<section class="holder-page">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<article>
						<img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHhtbDpzcGFjZT0icHJlc2VydmUiIHZpZXdCb3g9IjAgMCAxMDAgMTAwIiB5PSIwIiB4PSIwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGlkPSLlnJblsaRfMSIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMjAwcHgiIGhlaWdodD0iMjAwcHgiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiBzdHlsZT0id2lkdGg6MTAwJTtoZWlnaHQ6MTAwJTtiYWNrZ3JvdW5kLXNpemU6aW5pdGlhbDtiYWNrZ3JvdW5kLXJlcGVhdC15OmluaXRpYWw7YmFja2dyb3VuZC1yZXBlYXQteDppbml0aWFsO2JhY2tncm91bmQtcG9zaXRpb24teTppbml0aWFsO2JhY2tncm91bmQtcG9zaXRpb24teDppbml0aWFsO2JhY2tncm91bmQtb3JpZ2luOmluaXRpYWw7YmFja2dyb3VuZC1jb2xvcjppbml0aWFsO2JhY2tncm91bmQtY2xpcDppbml0aWFsO2JhY2tncm91bmQtYXR0YWNobWVudDppbml0aWFsO2FuaW1hdGlvbi1wbGF5LXN0YXRlOnBhdXNlZCIgPjxnIGNsYXNzPSJsZGwtc2NhbGUiIHN0eWxlPSJ0cmFuc2Zvcm0tb3JpZ2luOjUwJSA1MCU7dHJhbnNmb3JtOnJvdGF0ZSgwZGVnKSBzY2FsZSgwLjgsIDAuOCk7YW5pbWF0aW9uLXBsYXktc3RhdGU6cGF1c2VkIiA+PGcgc3R5bGU9ImFuaW1hdGlvbi1wbGF5LXN0YXRlOnBhdXNlZCIgPjxwYXRoIGZpbGw9IiMzMzMiIGQ9Ik02NS44NzcgNTcuNTAzYTQuMjM0IDQuMjM0IDAgMCAxLTQuMjM0LTQuMjM0VjI2LjEwMmMwLTQuNjY5LTMuNzk5LTguNDY4LTguNDY4LTguNDY4aC02LjM1MmMtNC42NjkgMC04LjQ2OCAzLjc5OS04LjQ2OCA4LjQ2OFY1My4yN2E0LjIzNCA0LjIzNCAwIDEgMS04LjQ2OCAwVjI2LjEwMmMwLTkuMzM4IDcuNTk3LTE2LjkzNSAxNi45MzUtMTYuOTM1aDYuMzUyYzkuMzM4IDAgMTYuOTM1IDcuNTk3IDE2LjkzNSAxNi45MzVWNTMuMjdhNC4yMyA0LjIzIDAgMCAxLTQuMjMyIDQuMjMzeiIgc3R5bGU9ImZpbGw6cmdiKDUxLCA1MSwgNTEpO2FuaW1hdGlvbi1wbGF5LXN0YXRlOnBhdXNlZCIgPjwvcGF0aD48L2c+CjxwYXRoIGZpbGw9IiNmOGIyNmEiIGQ9Ik03MS44NzUgODcuNTYzaC00My43NWE5LjU4NCA5LjU4NCAwIDAgMS05LjU4NC05LjU4NFY0My4yMThhOS41ODQgOS41ODQgMCAwIDEgOS41ODQtOS41ODRoNDMuNzQ5YTkuNTg0IDkuNTg0IDAgMCAxIDkuNTg0IDkuNTg0djM0Ljc2MWMuMDAxIDUuMjkzLTQuMjkgOS41ODQtOS41ODMgOS41ODR6IiBzdHlsZT0iZmlsbDpyZ2IoMjQ4LCAxNzgsIDEwNik7YW5pbWF0aW9uLXBsYXktc3RhdGU6cGF1c2VkIiA+PC9wYXRoPgo8bWV0YWRhdGEgeG1sbnM6ZD0iaHR0cHM6Ly9sb2FkaW5nLmlvL3N0b2NrLyIgc3R5bGU9ImFuaW1hdGlvbi1wbGF5LXN0YXRlOnBhdXNlZCIgPjxkOm5hbWUgc3R5bGU9ImFuaW1hdGlvbi1wbGF5LXN0YXRlOnBhdXNlZCIgPmxvY2s8L2Q6bmFtZT4KPGQ6dGFncyBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6cGF1c2VkIiA+bG9jayxzZWN1cmUscHJpdmF0ZSxzZWN1cml0eSxndWFyZCxodHRwcyxzc2wsZW5jcnlwdCxwcm90ZWN0LGxvY2tzbWl0aDwvZDp0YWdzPgo8ZDpsaWNlbnNlIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTpwYXVzZWQiID5ieTwvZDpsaWNlbnNlPgo8ZDpzbHVnIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTpwYXVzZWQiID51ZDM5eDwvZDpzbHVnPjwvbWV0YWRhdGE+PC9nPjwhLS0gZ2VuZXJhdGVkIGJ5IGh0dHBzOi8vbG9hZGluZy5pby8gLS0+PC9zdmc+" alt="Padlock Icon" style="background-color: white;border-radius: 50%;padding:20px;" />
                        <?php echo wp_kses_post($HTML); ?>
						<p><a href="https://ss88.us/plugins/two-factor-2fa-authentication-via-email-plugin-for-wordpress" target="_blank"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABYCAMAAAA0hKKwAAAAflBMVEVHcEz/////////////////////////////////////////////////////////////////////////////////////////////////////AHj/C33/AHP/AG//GYb/4vD/TqL/l8n/yuT/Opf/udr/8fj/Z6//rdT/ebj/AGwXRMjbAAAAGXRSTlMA92/jE08Ht5/t2l4ekyuCN0KstMvFwNDHalugRAAABEZJREFUaN7tWtmSqjAQDatACCCICgnIJjr//4MXREeWbLg83Ko55ZN0cugl6e4EAGgwPUP1rc0Nlg+3LlVo60eOrTkd9tEmRKrhmUAS+tYPD5qdjGA7saVu9bGUC9FuItNB0Q6hb+hiBhh2Q5WEBjv2t8Oren5MF+mH2huVy6P7WsKHZhmuhxSBlG15TA7VTiSgSAkhqhuBd0w+CRtSOAwl+TCsRazB5PPYzwLASL6B40QXT/kKSRKO1+4u+RLUUewmX8PvgnHt75FEDxI/+SKCu0fsb5JYoiVyPp9OJ+E05x7slT8slpD6sLkWeYc0z4trw2Q6lW3di+G8LsqKKmIwrdXUJCNp2v1Skv0QXFyXM5ybtk6zjPRISSeW1hXLXgHlQUmydIJurry9nMcqFHmWzaVISVn2jB2lIb0KeDoDybK8uNwMV5UFJjeZGQip6E6hkNSU8XceXJRljX8yukCa1cvZXCrJCadsZAsj3fAYgpcxsqWSNNnCWJg1L549JA01vJYkFzKaBz8m4an3FMgusiRZ+jKILEkleFuKsZ7/n+gkCjW68NJAT7MxTUeKRNLxI6c8JxW6ZJCo6CmFlt7bjBNVi6gdxTdlxSs6q4YoSCrjmBkTjSM5sPaukS7TCTFHQ4IvzIRi0uvfEpPJlFhgMEIKekKAvOx7amnbJEsZUl8YVfGQtDxWRmqKlEjFFEmLRlR6RczcWT1opitupgRuK3Zy3t5JAk4Cr1pMuMYi+MorA/a/xV3IrRPKnG61QbvrSaYikqjuOudQPU5SnqF6oDVFfVNT1g2pBRTJYVLWi4vI63yTJHkp7LZmraMlZGnyqWeuwrJP2c5bLTHLuR0XDFWyngMAlEgq09mKtGdxZxpQO+xEQpmbyUgpFt3Re2xgaOKxl35tXsRyyGSds+ihePSpxmJ3aAbv4MNwJGjEaggOcXT/7ZZoH4iPolz0Vr99gHInXt7rNI4KpOG+ZjQHmmANXF9bb6h1FLcQUJ01FEcIXoIpT+O8SDHQSPnGVsFbcCX2TcsF7yLYvXAOuN5mXGUcD3wGnISmuR/iADrT/UoAPgZfpuR5F57wYO4TvmfsMQ74JBhhvPv/SFjmktx3kbXVv+l4F1oI7G+XIuaLIewLtiR1b/c9yuZ++cLTx2WmY4Wz4AP/MAht7iTDJQ+Dx+WkFcdlXIyh56ARSb+hRirlgo1fWGqQ5obJkCnJ7dUiH3qu+XsXqAovC+LRm+keRLu5cZckg6mdOLQsK4wlqwlt34tb0VGjXXkxSD6LP5I/Eul6FH2fJNZX37NtQPzCXauxrrGIwara/VEtutGKQZq6qkUY3X9D2fZF84e93YSRRP+mWMG0fZGhOYy/MnDViGtn5aguEocJN/x3c9DidEUPVBQ72nycovWbPyMB6gbaOPaCSrGdPYLMbz/0AP5+S9J/ToJUIzBFX4dAH4Wb8RgYTHPsPxIzdm/MInasAAAAAElFTkSuQmCC" style="max-width:20px;" alt="SS88 LLC Logo" /></a></p>
					</article>
				</div>
			</div>
		</div>
	</section>

</body>
</html>
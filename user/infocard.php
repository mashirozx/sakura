<style>
			.smt-app{position:relative}.smt-app .sm-fixed{z-index:10000000 !important;position:fixed}.smt-app .sm-fixed-2{z-index:11000000 !important}.smt-app,.smt-app *{letter-spacing:normal;font-size:14px;line-height:normal;font-weight:normal;margin:0}.smt-app img{user-select:none}.smt-app .sm-shadow{box-shadow:rgba(0,0,0,0.1) 0px 4px 12px}.smt-wrapper{background:none;padding:0;width:auto;height:auto}.hover-opacity{cursor:pointer;transition:opacity 200ms linear}.hover-opacity:hover{opacity:0.95 !important}.powered-link{font-size:14px !important;color:#555;text-decoration:none;margin-top:5px;display:inline-block;white-space:nowrap}.powered-link img{width:14px !important;height:14px !important}.icon-wrapper+.powered-link{position:absolute;left:50%;transform:translateX(-55%) !important}.sm-disable-animation{animation-duration:0ms !important}.sm-close{padding:0;line-height:normal;position:absolute;color:#fff;top:12px;right:12px;width:24px;height:24px;font-size:22px;cursor:pointer;text-align:center;z-index:1;font-style:normal;outline:none;border:none;box-shadow:none}.material-icons{font-family:'Material Icons' !important}
			.smt-wrapper.top-left{left:30px;top:25px}.smt-wrapper.top-right{top:25px;right:30px}.smt-wrapper.bottom-left{bottom:25px;left:30px}.smt-wrapper.bottom-right{bottom:25px;right:30px}.smt-wrapper.bottom,.smt-wrapper.top,.smt-wrapper.bottom-center,.smt-wrapper.top-center{left:50%;transform:translateX(-50%);right:initial}.smt-wrapper.bottom,.smt-wrapper.bottom-center{bottom:25px !important}.smt-wrapper.top,.smt-wrapper.top-center{top:25px !important}.smt-wrapper.left,.smt-wrapper.right{top:50%;transform:translateY(-50%)}.smt-wrapper.left{left:30px}.smt-wrapper.right{right:30px}.smt-app .icon-wrapper{box-sizing:border-box;height:60px;width:60px;padding:0;border-radius:50%;box-shadow:5px 0px 10px rgba(0,0,0,0.5);cursor:pointer;transition:opacity 300ms linear;display:block}.smt-app .icon-wrapper img{height:100% !important;width:100% !important;display:inline !important;min-height:auto !important}
			.smt-app-flash_cards .smt-wrapper{width:auto !important;height:auto !important}.smt-app-flash_cards .flash-cards-block{position:relative;color:black;font-size:14px;background:white;border-radius:3px;max-width:400px;min-width:50px;padding:20px 15px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden;border:1px solid #ccc;box-shadow:0 3px 7px rgba(0,0,0,0.12);width:330px}.smt-app-flash_cards .flash-cards-block:not(.show){display:none}.smt-app-flash_cards .flash-cards-block.show{animation:puff-in-center 0.5s cubic-bezier(0.47, 0, 0.745, 0.715) both}.smt-app-flash_cards .flash-cards-block .flash-cards-header{display:flex;justify-content:space-between;flex:1 1 auto}.smt-app-flash_cards .flash-cards-block .flash-cards-header .flash-cards-title{flex:1 1 auto;font-size:16px;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.smt-app-flash_cards .flash-cards-block .flash-cards-header .flash-cards-close{flex:0 0 auto}.smt-app-flash_cards .flash-cards-block .flash-cards-message{line-height:1.5;color:#444;font-size:14px;font-weight:400;font-family:Montserrat, sans-serif;white-space:normal !important}.smt-app-flash_cards .flash-cards-block .flash-cards-button{margin-top:1em;border-radius:3px !important;border:1px solid !important;box-shadow:none !important;text-decoration:none !important;font-size:1.2em !important;padding:5px 0 !important;width:100%;display:block;text-align:center;cursor:pointer;transition:color, background-color 400ms linear}.smt-app-flash_cards .flash-cards-block .flash-cards-button:not(:hover){background-color:#fff !important}.smt-app-flash_cards .flash-cards-block .flash-cards-button:hover{border-color:white !important;color:white !important}.smt-app-flash_cards .powered-link{text-decoration:underline !important;display:block;text-align:center;margin-bottom:-10px;margin-top:10px;color:#540bfa;font-size:10px}@keyframes puff-in-center{0%{-webkit-transform:scale(2);transform:scale(2);-webkit-filter:blur(4px);filter:blur(4px);opacity:0}100%{-webkit-transform:scale(1);transform:scale(1);-webkit-filter:blur(0px);filter:blur(0px);opacity:1}}@keyframes puff-out-center{0%{transform:scale(1);filter:blur(0px);opacity:1}100%{transform:scale(2);filter:blur(4px);opacity:0}}
		</style>
		
		<div class="smt-app smt-app-flash_cards force-mobile">
			<div class="smt-wrapper sm-fixed bottom-right" style="z-index: 2147483647;">

				<div class="flash-cards-block show">

					<div class="flash-cards-header">
						<div class="flash-cards-title">INFO</div>
						<div class="flash-cards-close hover-opacity" onclick="javascript:document.querySelector('.smt-app-flash_cards').remove()">
							<style>
								.smt-flash_cards-clear-svg .path-main {fill: #777 !important;} .smt-flash_cards-clear-svg .path-addition {fill: none !important;}
							</style>
							<svg class="smt-flash_cards-clear-svg" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
								<path fill="#777" class="path-main" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
								<path d="M0 0h24v24H0z" class="path-addition" fill="none" ></path>
							</svg>
						</div>
					</div>

					<div class="flash-cards-message"><?php echo akina_option('notice_infocard');?></div>

					<a style="color: <?php echo akina_option('theme_skin');?>; border-color: <?php echo akina_option('theme_skin');?>; background-color: <?php echo akina_option('theme_skin');?>" class="flash-cards-button" onclick="javascript:document.querySelector('.smt-app-flash_cards').remove()">
						Get it!
					</a>
				</div>

			</div>
		</div>

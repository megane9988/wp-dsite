<?php
/**
 * Handle settings
 * @package PhotoDropper
 * @author Nicky Hajal
 */
if(!class_exists("Pdr_Ajax_Action_Store")){
	class Pdr_Ajax_Action_Store extends Pdr_AjaxAction{

		/**
		 * Handle Settings
		 */
		public function action()
		{
			global $PDR_UTIL;
			global $current_user;
			get_currentuserinfo();
			$id = $current_user->ID;
			if (isset($this->p['store'])) {
				$meta = get_user_meta($id, 'pdrp_store', true);
				if (!strlen($meta)) {
					$meta = '{}';
				}
				$meta = json_decode($meta);
				foreach($this->p['store'] as $k => $v) {
					$meta->$k = $v;
				}
				$meta = update_user_meta($id, 'pdrp_store', json_encode($meta));
			}
		}
	}
}
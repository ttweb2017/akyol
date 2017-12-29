<?php
class ControllerExtensionModuleMyLiveChat extends Controller {
	public function index() {
		$this->load->language ( 'module/mylivechat' );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		$mylivechatid = $this->config->get ( 'mylivechat_code' );
		$displaytype = $this->config->get ( 'mylivechat_displaytype' );
		$tempstr = "<script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatinline.aspx?hccid=" . $mylivechatid . "\"></script>";
		switch ($displaytype) {
			case "0" :
				$tempstr = "<script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatinline.aspx?hccid=" . $mylivechatid . "\"></script>";
				break;
			case "1" :
				$tempstr = "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatbutton.aspx?hccid=" . $mylivechatid . "\"></script>";
				break;
			case "2" :
				$tempstr = "<script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatwidget.aspx?hccid=" . $mylivechatid . "\"></script>";
				break;
			case "3" :
				$tempstr = "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatbox.aspx?hccid=" . $mylivechatid . "\"></script>";
				break;
			case "4" :
				$tempstr = "<div id=\"MyLiveChatContainer\"></div><script type=\"text/javascript\" async=\"async\" defer=\"defer\" data-cfasync=\"false\" src=\"https://www.mylivechat.com/chatlink.aspx?hccid=" . $mylivechatid . "\"></script>";
				break;
			default :
				break;
		}
		$data ['code'] = $tempstr;
		
		return $this->load->view('extension/module/mylivechat.tpl', $data);
	}
}
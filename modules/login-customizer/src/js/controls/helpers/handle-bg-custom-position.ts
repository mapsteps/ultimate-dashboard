declare var wp: any;

const handleBgCustomPostion = (keyPrefix: string, position: string) => {
	if (position == "custom") {
		wp.customize
			.control("udb_login[" + keyPrefix + "bg_custom_position]")
			.activate();
	} else {
		wp.customize
			.control("udb_login[" + keyPrefix + "bg_custom_position]")
			.deactivate();
	}
};

export default handleBgCustomPostion;

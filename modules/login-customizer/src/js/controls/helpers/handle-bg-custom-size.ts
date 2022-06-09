declare var wp: any;

const handleBgCustomSize = (keyPrefix: string, size: string) => {
	if (size == "custom") {
		wp.customize
			.control("udb_login[" + keyPrefix + "bg_custom_size]")
			.activate();
	} else {
		wp.customize
			.control("udb_login[" + keyPrefix + "bg_custom_size]")
			.deactivate();
	}
};

export default handleBgCustomSize;

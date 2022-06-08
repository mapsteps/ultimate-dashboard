import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";

interface ListenBgSectionChangeOpts {
	sectionName: string;
	keyPrefix: string;
}

declare var wp: any;

const listenBgSectionState = (opts: ListenBgSectionChangeOpts) => {
	const sectionName = opts.sectionName;
	const keyPrefix = opts.keyPrefix;

	wp.customize.section(sectionName, function (section: any) {
		section.expanded.bind(function (isExpanded: boolean | number) {
			if (isExpanded) {
				var bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				if (wp.customize("udb_login[" + keyPrefix + "bg_image]").get()) {
					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.activate();

					handleBgCustomPostion(keyPrefix, bgPosition);

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_size]")
						.activate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.activate();
				} else {
					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_horizontal_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_vertical_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_size]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.deactivate();
				}
			}
		});
	});
}

export default listenBgSectionState;
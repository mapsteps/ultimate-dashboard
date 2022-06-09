import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";
import handleBgCustomSize from "../../helpers/handle-bg-custom-size";

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
				const bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				const bgSize = wp
					.customize("udb_login[" + keyPrefix + "bg_size]")
					.get();

				if (wp.customize("udb_login[" + keyPrefix + "bg_image]").get()) {
					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.activate();

					handleBgCustomPostion(keyPrefix, bgPosition);
					handleBgCustomSize(keyPrefix, bgSize);

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.activate();
				} else {
					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_custom_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_size]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_custom_size]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.deactivate();
				}
			}
		});
	});
};

export default listenBgSectionState;

(function () {
	init();

	function init() {
		patchWpMetabox();

		const metabox = document.querySelector(".tags-heatbox");
		if (!metabox) return;

		const tags = metabox.querySelectorAll("code");
		if (!tags.length) return;

		tags.forEach((tag) => {
			tag.addEventListener("click", handleTagClick);
		});
	}

	function patchWpMetabox() {
		const metaboxById = document.querySelector("#udb-tags-metabox");

		if (metaboxById) {
			const styleTag = document.createElement("style");
			styleTag.innerHTML = `
				.tags-heatbox, .tags-heatbox * {
					box-sizing: border-box;
				}

				#udb-tags-metabox h2 .action-status {
					right: 0;
				}
			`;
			document.head.appendChild(styleTag);

			metaboxById.classList.add("heatbox");
			metaboxById.classList.add("tags-heatbox");

			const metaboxContent = metaboxById.querySelector(".inside");

			if (metaboxContent) {
				metaboxContent.classList.add("heatbox-content");
			}
		}
	}

	/**
	 * Handle tag click.
	 *
	 * @param {Event} e The event object.
	 */
	async function handleTagClick(e) {
		const tag = e.target;
		if (!(tag instanceof HTMLElement)) return;

		const value = tag.innerText;
		if (!value) return;

		// Copy value to clipboard.
		await copyToClipboard(value);

		const notice = document.querySelector(".tags-heatbox .action-status");
		if (!notice) return;

		notice.classList.add("is-shown");

		setTimeout(() => {
			notice.classList.remove("is-shown");
		}, 1500);
	}

	/**
	 * Copy text to clipboard.
	 *
	 * @param {string} text The text to copy.
	 */
	async function copyToClipboard(text) {
		try {
			await navigator.clipboard.writeText(text);
		} catch (err) {
			// console.error("Unable to copy text to clipboard:", err);
			await copyToClipboardViaExecCommand(text);
		}
	}

	/**
	 * Copy text to clipboard via exec command.
	 *
	 * @param {string} text The text to copy.
	 */
	async function copyToClipboardViaExecCommand(text) {
		const textArea = document.createElement("textarea");

		textArea.value = text;
		textArea.style.position = "fixed";
		textArea.style.top = "-3px";
		textArea.style.left = "-3px";
		textArea.style.width = "1px";
		textArea.style.height = "1px";
		textArea.style.background = "transparent";
		textArea.style.opacity = "0";

		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();

		try {
			document.execCommand("copy");
		} catch (err) {
			console.error("Unable to copy text to clipboard:", err);
		}

		document.body.removeChild(textArea);
	}
})();

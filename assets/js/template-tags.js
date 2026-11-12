/**
 * Placeholder tags: click a tag to copy it.
 *
 * In a heatbox tags box (admin menu & admin bar editors) the "Copied" status in the box title shows;
 * in the widget screen's sidebar box (a normal WordPress box) the tag itself says "Copied!" for a moment.
 */
(function () {
	init();

	function init() {
		document.querySelectorAll(".tags-heatbox code, #udb-tags-metabox code").forEach(function (tag) {
			tag.addEventListener("click", handleTagClick);
		});
	}

	/**
	 * Handle tag click.
	 *
	 * @param {Event} e The event object.
	 */
	async function handleTagClick(e) {
		const tag = e.currentTarget;
		const value = tag.dataset.tag || tag.innerText;

		if (!value || tag.classList.contains("is-copied")) return;

		await copyToClipboard(value);

		const notice = tag.closest(".tags-heatbox")?.querySelector(".action-status");

		if (notice) {
			notice.classList.add("is-shown");

			setTimeout(() => {
				notice.classList.remove("is-shown");
			}, 1500);

			return;
		}

		tag.dataset.tag = value;
		tag.textContent = tag.closest("[data-copied-text]")?.dataset.copiedText || "Copied!";
		tag.classList.add("is-copied");

		setTimeout(() => {
			tag.textContent = value;
			tag.classList.remove("is-copied");
		}, 1200);
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

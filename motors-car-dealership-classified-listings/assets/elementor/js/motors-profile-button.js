'use strict'

const buttons = document.querySelectorAll('.motors-profile-button')
const dropdowns = document.querySelectorAll('.lOffer-account-dropdown')
let hideTimeout

function showDropdown(dropdown) {
	clearTimeout(hideTimeout)
	dropdown.style.visibility = 'visible'
	dropdown.style.opacity = '1'
	dropdown.style.pointerEvents = 'auto'
}

function hideDropdown(dropdown) {
	hideTimeout = setTimeout(() => {
		if (!isInputFocused(dropdown) && !isMouseOverDropdown(dropdown)) {
			dropdown.style.visibility = 'hidden'
			dropdown.style.opacity = '0'
			dropdown.style.pointerEvents = 'none'
		}
	}, 200)
}

function isInputFocused(dropdown) {
	return (
		dropdown.querySelector('input:focus, select:focus, textarea:focus') !== null
	)
}

function isMouseOverDropdown(dropdown) {
	return dropdown.matches(':hover')
}

buttons.forEach((button, index) => {
	const dropdown = dropdowns[index]

	if (dropdown) {
		button.addEventListener('mouseover', () => {
			if (!isInputFocused(dropdown)) {
				showDropdown(dropdown)
			}
		})
		button.addEventListener('mouseout', () => hideDropdown(dropdown))

		dropdown.addEventListener('mouseover', () => showDropdown(dropdown))
		dropdown.addEventListener('mouseout', () => hideDropdown(dropdown))

		const inputs = dropdown.querySelectorAll('input, select, textarea')
		inputs.forEach(input => {
			input.addEventListener('focus', () => showDropdown(dropdown))
			input.addEventListener('blur', () => hideDropdown(dropdown))
		})
	}
})

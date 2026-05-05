let cart = []

const calculateTotal = () => cart.reduce((sum, item) => sum + item.price, 0)

const removeFromCart = index => {
	cart.splice(index, 1)
	renderCart()
}

const renderCart = () => {
	const cartList = document.getElementById('cart-items')
	const totalSpan = document.getElementById('cart-total')

	cartList.innerHTML = ''

	cart.forEach((item, index) => {
		const li = document.createElement('li')
		li.textContent = `${item.name} — ${item.price} руб. `

		const removeBtn = document.createElement('button')
		removeBtn.textContent = 'Удалить'
		removeBtn.addEventListener('click', () => removeFromCart(index))

		li.appendChild(removeBtn)
		cartList.appendChild(li)
	})

	const total = calculateTotal()
	totalSpan.textContent = `Итого: ${total} руб.`
}

const addToCart = product => {
	cart.push(product)
	renderCart()
}

document.querySelectorAll('.add-to-cart').forEach(button => {
	button.addEventListener('click', () => {
		const movieBlock = button.closest('.movie')
		const name = movieBlock.querySelector('p a').textContent
		const price = Number(movieBlock.dataset.price)

		const product = { id: Date.now(), name, price }
		addToCart(product)
	})
})

document.getElementById('checkout').addEventListener('click', () => {
	if (cart.length === 0) {
		alert('Корзина пуста!')
	} else {
		alert('Покупка прошла успешно!')
		cart = []
		renderCart()
	}
})

document.getElementById('clear-cart').addEventListener('click', () => {
	cart = []
	renderCart()
})

const filterMovies = () => {
	const selectedGenre = document.getElementById('genre-filter').value
	document.querySelectorAll('.movie').forEach(movie => {
		const genre = movie.dataset.genre
		if (selectedGenre === 'all' || genre === selectedGenre) {
			movie.style.display = 'inline-block'
		} else {
			movie.style.display = 'none'
		}
	})
}

document.getElementById('genre-filter').addEventListener('change', filterMovies)

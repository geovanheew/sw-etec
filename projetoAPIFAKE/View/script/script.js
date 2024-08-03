import BuscarUsuario from './BuscarUsuario.js'
import BuscarUnico from './BuscarUnico.js'
import LimitarResultados from './LimitarResultados.js'
import OrdenarResultados from './OrdenarResultados.js'
import AdicionarUsuario from './AdicionarUsuario.js'
import AtualizarUsuario from './AtualizarUsuario.js'
import DeletarUsuario from './DeletarUsuario.js'

document.addEventListener('DOMContentLoaded', () => {
  const links = document.querySelectorAll('.sidebar a')
  const sections = document.querySelectorAll('.content-section')
  const headerLink = document.getElementById('header-link')

  function mostrarSecao(idAlvo) {
    sections.forEach(section => {
      section.classList.remove('active')
    })
    document.getElementById(idAlvo).classList.add('active')
  }

  function atualizarBotao(button) {
    const loadingText = button.querySelector('.loading-text')
    const buttonText = button.querySelector('.button-text')

    buttonText.style.visibility = 'hidden'
    loadingText.style.visibility = 'visible'
    loadingText.textContent = 'Buscando'
    button.classList.add('loading')

    let pontos = 0
    const intervalo = setInterval(() => {
      if (pontos < 3) {
        loadingText.textContent += '.'
        pontos++
      } else {
        loadingText.textContent = 'Buscando'
        pontos = 0
      }
    }, 500)

    return intervalo
  }

  function finalizarBotao(button, intervalo) {
    clearInterval(intervalo)
    button.classList.remove('loading')
    const loadingText = button.querySelector('.loading-text')
    loadingText.textContent = ''
    loadingText.style.visibility = 'hidden'
    button.querySelector('.button-text').style.visibility = 'visible'
  }

  links.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault()
      const idAlvo = link.getAttribute('data-target')
      mostrarSecao(idAlvo)

      document.querySelectorAll('.fetch-button').forEach(button => {
        button.removeEventListener('click', tratarCliqueBotao)
      })

      switch (idAlvo) {
        case 'get-all':
          const fetchUsersButton = document.getElementById('fetch-users')
          if (fetchUsersButton) {
            fetchUsersButton.addEventListener('click', tratarCliqueBotao)
          }
          break
        case 'get-single':
          const fetchUnicoButton = document.getElementById('fetchUnicoButton')
          if (fetchUnicoButton) {
            fetchUnicoButton.addEventListener('click', tratarCliqueBotao)
          }
          break
        case 'limit-results':
          const limitResultsButton = document.getElementById('fetch-limited-users')
          if (limitResultsButton) {
            limitResultsButton.addEventListener('click', tratarCliqueBotao)
          }
          break
        case 'sort-results':
          const sortResultsButton = document.getElementById('sort-results-button')
          if (sortResultsButton) {
            sortResultsButton.addEventListener('click', tratarCliqueBotao)
          }
          break
        case 'add':
          const addUserButton = document.getElementById('add-user')
          if (addUserButton) {
            addUserButton.addEventListener('click', tratarCliqueBotao)
          }
          break
        case 'update':
          const updateUserButton = document.getElementById('update-user-button')
          if (updateUserButton) {
            updateUserButton.addEventListener('click', tratarCliqueBotao)
          }
          break
        case 'delete':
          const deleteUserButton = document.getElementById('delete-user-button')
          if (deleteUserButton) {
            deleteUserButton.addEventListener('click', tratarCliqueBotao)
          }
          break
      }
    })
  })

  headerLink.addEventListener('click', (e) => {
    e.preventDefault()
    mostrarSecao('default')
  })

  function tratarCliqueBotao(e) {
    const button = e.target.closest('.fetch-button')
    if (!button) return

    const id = button.id
    const intervalo = atualizarBotao(button)

    switch (id) {
      case 'fetch-users':
        BuscarUsuario().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
      case 'fetchUnicoButton':
        BuscarUnico().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
      case 'fetch-limited-users':
        LimitarResultados().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
      case 'sort-results-button':
        OrdenarResultados().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
      case 'add-user':
        AdicionarUsuario().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
      case 'update-user-button':
        AtualizarUsuario().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
      case 'delete-user-button':
        DeletarUsuario().finally(() => {
          finalizarBotao(button, intervalo)
        })
        break
    }
  }
})

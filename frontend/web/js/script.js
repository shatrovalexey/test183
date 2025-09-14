const { createApp, ref, onMounted } = Vue;
const cssSelector = "#translators";

createApp({
	setup() {
		const obj = document.querySelector(cssSelector);
		const translators = ref([]);
		const error = ref(null);
		const loading = ref(true);
		const fetchData = url => fetch(url).then(data => data.json());
		const fetchTranslators = () => {
			loading.value = true;
			fetchData(obj.dataset.src)
				.then(data => {
					translators.value = data.map(translator => ({
						...translator,
						profile: null,
						loading: false,
						error: null,
						loaded: false
					}));
				})
				.catch(err => error.value = err.message)
				.finally(() => loading.value = false);
		};
		const fetchProfile = (translator, event) => {
			const { open, dataset: { src } } = event.target;
			if (open && !translator.loaded) {
				translator.loading = true;
				fetchData(src)
					.then(data => {
						// Добавляем schedule к каждому переводу
						translator.profile = data.map(translation => ({
							...translation,
							schedule: null,
							scheduleLoaded: false,
							scheduleLoading: false
						}));
						translator.loaded = true;
					})
					.catch(err => translator.error = err.message)
					.finally(() => translator.loading = false);
			}
		};
		const fetchSchedule = (translation, event) => {
			const { open, dataset: { src } } = event.target;
			if (open && !translation.scheduleLoaded) {
				translation.scheduleLoading = true;
				fetchData(src)
					.then(data => {
						translation.schedule = data;
						translation.scheduleLoaded = true;
					})
					.catch(err => console.error('Ошибка загрузки расписания:', err))
					.finally(() => translation.scheduleLoading = false);
			}
		};
		
		onMounted(fetchTranslators);
		
		return {translators, error, loading, fetchProfile, fetchSchedule,};
	}
}).mount(cssSelector);
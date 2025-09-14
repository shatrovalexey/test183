const { createApp, ref, onMounted } = Vue;
const cssSelectorTrans = "#translators";

createApp({
    setup() {
        const translatorsObj = document.querySelector(cssSelectorTrans);
        const translators = ref([]);
        const error = ref(null);
        const loading = ref(true);
        const fetchData = url => fetch(url).then(data => data.json());
        const fetchTranslators = () => {
            loading.value = true;

            fetchData(translatorsObj.dataset.src)
                .then(data => translators.value = data
                    .map(translator => ({... translator, profile: null, loading: false, error: null, loaded: false})))
                .catch(err => error.value = err.message)
                .finally(() => loading.value = false);
        };
        const fetchProfile = (translator, evt) => {
            const { open, dataset: { src } } = evt.target;

            if (open && !translator.loaded) {
                translator.loading = true;
                fetchData(src)
                    .then(data => {
                        translator.profile = data
                            .map(translation => ({...translation, schedule: null, scheduleLoaded: false, scheduleLoading: false}));
                        translator.loaded = true;
                    })
                    .catch(exception => translator.error = exception.message)
                    .finally(() => translator.loading = false);
            }
        };
        const fetchSchedule = (translation, evt) => {
            const { open, dataset: { src } } = evt.target;

            if (open && !translation.scheduleLoaded) {
                translation.scheduleLoading = true;
                fetchData(src)
                    .then(data => {
                        translation.schedule = data;
                        translation.scheduleLoaded = true;
                    })
                    .catch(exception => console.error(`Ошибка загрузки расписания: ${exception}`))
                    .finally(() => translation.scheduleLoading = false);
            }
        };
        
        onMounted(fetchTranslators);
        
        return {translators, error, loading, fetchProfile, fetchSchedule,};
    }
}).mount(cssSelectorTrans);
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Função global para o Wizard para garantir acessibilidade em swaps HTMX
window.weeklyReportWizard = (initialData = {}) => ({
    step: parseInt(initialData.step) || 1,
    submitting: false,
    cellId: initialData.cellId || '',
    meetingLocation: initialData.meetingLocation || '',
    meetingDate: initialData.meetingDate || new Date().toISOString().split('T')[0],
    cellMembers: [],
    presentMemberIds: initialData.presentMemberIds || [],
    visitorList: initialData.visitorList || [],
    visitors: initialData.visitors || 0,
    children: initialData.children || 0,
    otherCellVisitors: initialData.otherCellVisitors || 0,
    committedMembers: initialData.committedMembers || 0,
    houseOfPeace: initialData.houseOfPeace || 0,
    mdasDone: initialData.mdasDone || 0,
    kgOfLove: initialData.kgOfLove || 0,
    conversions: initialData.conversions || 0,
    reconciliations: initialData.reconciliations || 0,
    offerPix: initialData.offerPix || 0,
    offerCash: initialData.offerCash || 0,
    notes: initialData.notes || '',

    async fetchMembers() {
        if (!this.cellId) return;
        try {
            const select = document.querySelector('select[name="cell_id"]');
            if (select && select.selectedIndex >= 0) {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.location) {
                    this.meetingLocation = selectedOption.dataset.location;
                }
            }

            const response = await fetch(`/api/cells/${this.cellId}/members`);
            if (!response.ok) throw new Error('Falha ao buscar membros');
            
            const data = await response.json();
            this.cellMembers = Array.isArray(data) ? data : [];
            
            if (this.presentMemberIds.length === 0 && this.cellMembers.length > 0) {
                this.presentMemberIds = this.cellMembers.map(m => m.id);
            }
            this.committedMembers = this.cellMembers.length;
        } catch (error) {
            console.error('Erro ao buscar membros:', error);
        }
    },

    toggleMember(id) {
        const index = this.presentMemberIds.indexOf(id);
        if (index > -1) {
            this.presentMemberIds.splice(index, 1);
        } else {
            this.presentMemberIds.push(id);
        }
    },

    toggleSelectAll() {
        if (this.presentMemberIds.length === this.cellMembers.length) {
            this.presentMemberIds = [];
        } else {
            this.presentMemberIds = this.cellMembers.map(m => m.id);
        }
    },

    addVisitor() {
        this.visitorList.push({ name: '' });
    },

    removeVisitor(index) {
        this.visitorList.splice(index, 1);
    },

    nextStep() {
        if (this.step === 1 && (!this.cellId || this.cellId === '')) {
            alert('Por favor, selecione uma célula primeiro.');
            return;
        }
        
        if (this.step < 5) {
            this.step++;
            this.scrollToTop();
        }
    },

    prevStep() {
        if (this.step > 1) {
            this.step--;
            this.scrollToTop();
        }
    },

    scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
        const main = document.querySelector('main');
        if (main) {
            main.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },

    stepTitle() {
        const titles = ['Identificação', 'Chamada da Célula', 'Impacto Pastoral', 'Financeiro', 'Revisão Final'];
        return titles[this.step - 1] || 'Relatório';
    },

    totalPresence() {
        return (parseInt(this.presentMemberIds.length) || 0) + 
               (parseInt(this.visitorList.length) || 0) + 
               (parseInt(this.children) || 0) + 
               (parseInt(this.otherCellVisitors) || 0);
    },

    totalOffer() {
        return (parseFloat(this.offerPix) || 0) + (parseFloat(this.offerCash) || 0);
    },

    formatMoney(value) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    },

    init() {
        this.$nextTick(() => {
            if (this.cellId) this.fetchMembers();
        });
        
        this.$watch('visitorList', () => {
            this.visitors = this.visitorList.length;
        });
    }
});

// Registro síncrono e imediato do componente de dados Alpine, evitando race conditions do Vite
Alpine.data('weeklyReportWizard', window.weeklyReportWizard);

if (!window.alpineStarted) {
    Alpine.start();
    window.alpineStarted = true;
}

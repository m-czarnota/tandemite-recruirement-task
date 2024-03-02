export class Client {
    constructor(
        public firstname: String = '',
        public lastname: String = '',
    ) {
    }

    public reset() {
        this.firstname = '';
        this.lastname = '';
    }
}
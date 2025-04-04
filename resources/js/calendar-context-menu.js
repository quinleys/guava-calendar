export default function calendarContextMenu() {
    return {

        open: false,

        size: {
            width: 0,
            height: 0,
        },
        position: {
            x: 0,
            y: 0,
        },
        mountData: {},
        context: null,

        menu: {
            ['x-show']() {
                return this.open
            },
            ['x-bind:style']() {
                return `
                position: absolute;
                z-index: 40;
                top: ${this.position.y}px;
                left: ${this.position.x}px;
                visibility: ${this.open ? 'visible' : 'hidden'};
                `;
            },
            ['x-on:click.away']() {
                this.closeMenu();
            }
        },

        init: async function () {
            const menu = this.$el.querySelector('[x-bind="menu"]');
            this.size = {
                width: menu.offsetWidth,
                height: menu.offsetHeight,
            };

            this.$el.addEventListener('calendar--open-menu', (event) => this.openMenu(event));
        },

        openMenu: function (event) {
            this.context = event.detail.context;
            this.mountData = event.detail.mountData;

            this.$nextTick(() => {
                // const clientX = event.detail.jsEvent.clientX;
                const clientY = event.detail.jsEvent.clientY;
                const pageX = event.detail.jsEvent.pageX;

                const pageY = event.detail.jsEvent.pageY;

                // if mobile device, don't calculate offset
                if (window.innerWidth < 1024) {
                    this.position.x = 0;
                    this.position.y = pageY;
                    this.open = true;
                    return;
                }

                // const offsetX = 300;
                const offsetY = clientY + this.size.height > window.innerHeight
                    ? clientY + this.size.height - window.innerHeight
                    : 0;

                this.position.x = (pageX - (window.innerWidth / 3)) + (this.size.width / 2);
                this.position.y = pageY - offsetY;
                this.open = true;
            });
        },

        closeMenu: function () {
            this.open = false;
        }
    }
}

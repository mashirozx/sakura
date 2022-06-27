/**
 * BotUI options
 * 
 * @interface BotUIOptions
 */
interface BotUIOptions {
    /**
     * Set this to true if you want to debug the underlaying Vue instance
     * 
     * @type {boolean}
     * @memberof BotUIOptions
     */
    debug?: boolean;
    /**
     * Set this to false if you already have FontAwesome in your project and don't want it to be loaded again by BotUI.
     * 
     * @type {boolean}
     * @memberof BotUIOptions
     */
    fontawesome?: boolean;
    /**
     * Set this to vue constructor when you use module loaded system e.g CMD or AMD.
     */
    vue?: any;
}

/**
 * Message method options
 * 
 * @interface MessageOption
 */
interface MessageOption {
    /**
     * Set to true if you want to show a loading state '3 animated dots'. 
     * Available in version >= 0.3.1
     * 
     * @type {boolean}
     * @memberof MessageOption
     */
    loading?: boolean;
    /**
     * Wait before showing the message. in milliseconds.
     * 
     * @type {number}
     * @memberof MessageOption
     */
    delay?: number;
    /**
     * Either 'text' or 'embed'.
     * If you set this to 'embed', BotUI will create an `iframe` element and set `content` as its `src`.
     * 
     * @type {('text' | 'embed')}
     * @memberof MessageOption
     */
    type: 'text' | 'embed';
    /**
     * Should be a URL if type is 'embed', text otherwise.
     * 
     * @type {string}
     * @memberof MessageOption
     */
    content: string;
    /**
     * Should be shown aligned to right side.
     * 
     * @type {false}
     * @memberof MessageOption
     */
    human?: false;
    /**
     * A string or array of custom CSS classes you want to be added.
     * 
     * @type {string|string[]}
     * @memberof MessageOption
     */
    cssClass?: string | string[];
}

/**
 * Actions method option
 * 
 * @interface ActionsOption
 */
interface BaseActionsOption {
    /**
     * Either 'text' or 'button'.
     * 
     * @type {('text' | 'button')}
     * @memberof ActionsOption
     */
    type: 'text' | 'button';
    /**
     * Array of 'ButtonObject' if type is 'button'. Object of 'TextObject' otherwise.
     * 
     * @type {ButtonObject[]|TextObject}
     * @memberof ActionsOption
     */
    action: ButtonObject[] | TextObject;
    /**
     * A string or array of custom CSS classes you want to be added.
     * 
     * @type {string|string[]}
     * @memberof ActionsOption
     */
    cssClass?: string | string[];
    /**
     * Should the actions sections be hidden when submitted.
     * 
     * @type {boolean}
     * @memberof ActionsOption
     */
    autoHide?: boolean;
    /**
     * Text from action is added as a message in UI from human side.
     * 
     * @type {boolean}
     * @memberof ActionsOption
     */
    addMessage?: boolean;
}

/**
 * Text action option.
 * 
 * @interface TextObject
 */
interface TextObject {
    /**
     * Size of the input to show. Relies on HTML size attribute for input elements.
     * 
     * @type {number}
     * @memberof TextObject
     */
    size?: number;
    /**
     * Could be any of the valid types for HTML input elements. e.g.: number, tel, time, date, email, etc.
     * 
     * @type {string}
     * @memberof TextObject
     */
    sub_type?: string;
    /**
     * Pre-populates the text field. Only for 'text' type.
     * 
     * @type {string}
     * @memberof TextObject
     */
    value: string;
    /**
     * Sets the placeholder text for the input element.
     * 
     * @type {string}
     * @memberof TextObject
     */
    placeholder?: string;
}

/**
 * Button object
 * 
 * @interface ButtonObject
 */
interface ButtonObject {
    /**
     * Icon to show in button.
     * 
     * @type {string}
     * @memberof ButtonObject
     */
    icon?: string;
    /**
     * Text to show in the button. be creative!
     * 
     * @type {string}
     * @memberof ButtonObject
     */
    text: string;
    /**
     * This is how you will identify the button when result is returned.
     * 
     * @type {string}
     * @memberof ButtonObject
     */
    value: string;
    /**
     * A string or array of custom CSS classes you want to be added.
     * 
     * @type {string|string[]}
     * @memberof ButtonObject
     */
    cssClass?: string | string[];
}

interface TextActionOption extends BaseActionsOption {
    action: TextObject;
}

interface ButtonActionOption extends BaseActionsOption {
    action: ButtonObject[];
}

/**
 * Result object.
 * 
 * @interface ResultObject
 */
interface ResultObject {
    /**
     * 'Text' or 'Button' Type of the action it was returned from.
     * 
     * @type {('Text' | 'Button')}
     * @memberof ResultObject
     */
    type: 'Text' | 'Button';
    /**
     * Text in the input in case type is 'text'. If type is 'button' then its the same as 'value' in button object.
     * 
     * @type {string}
     * @memberof ResultObject
     */
    value: string;
    /**
     * Only present if type of message is 'button'. same as the 'text' in button object.
     * 
     * @type {string}
     * @memberof ResultObject
     */
    text: string;
}

declare class BotUI {


    constructor(id: string, opts?: BotUIOptions);

    message: {
        /**
         * Appends a message to UI.
         * 
         * @param {(MessageOption | string)} message 
         * @returns {Promise<number>} 
         */
        add(message: MessageOption | string): Promise<number>;
        /**
         * Appends a message to UI. Just a shorthand to `message.add`.
         * 
         * @param {(MessageOption | string)} message 
         * @returns {Promise<number>} 
         */
        bot(message: MessageOption | string): Promise<number>;
        /**
         * Appends a message to UI and sets the `human` to `true`.
         * 
         * @param {(MessageOption | string)} message 
         * @returns {Promise<number>} 
         */
        human(message: MessageOption | string): Promise<number>;
        /**
         * Accepts an index of message.
         * 
         * @param {number} index                
         * @returns {Promise<MessageOption>} 
         */
        get(index: number): Promise<MessageOption>;
        /**
         * Updates a message in UI.
         * "Only content and loading property of message can updated. type of a message cannot be changed."
         * 
         * @param {number} index 
         * @param {MessageOption} message 
         * @returns {Promise<number>} 
         */
        update(index: number, message: MessageOption): Promise<string>;
        /**
         * Removes a message from UI.
         * 
         * @param {number} index 
         * @returns {Promise<void>} 
         */
        remove(index: number): Promise<void>;
        /**
         * Removes all the messages from UI.
         * 
         * @param {number} index 
         * @returns {Promise<void>} 
         */
        removeAll(index: number): Promise<void>;
    }

    action: {
        /**
         * Shows the action section.
         * 
         * @returns {Promise<ResultObject>} 
         */
        show(action: BaseActionsOption): Promise<ResultObject>;
        /**
         * Hides the action section.
         * 
         * @returns {Promise<void>} 
         */
        hide(): Promise<void>;
        /**
         * Shows the action section and sets the action type to text. Its a shorthand to show.
         * 
         * @param {ActionsOption} action 
         * @returns {Promise<ResultObject>} 
         */
        text(action: TextActionOption): Promise<ResultObject>;
        /**
         * Shows the action section and sets the action type to button. Its a shorthand to show.
         * 
         * @param {ActionsOption} action 
         * @returns {Promise<ResultObject>} 
         */
        button(action: ButtonActionOption): Promise<ResultObject>;
    }
}

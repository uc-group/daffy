import InstructionLayer from './components/layers/InstructionLayer';
import PhpExtensionLayer from './components/layers/PhpExtensionLayer';
import instructionConfig from './instruction-config';

export default {
    instruction: {
        label: 'Instruction',
        component: InstructionLayer,
        multiple: true,
        teaser(layer) {
            let label = '';
            if (instructionConfig.hasOwnProperty(layer.args.instruction)) {
                label = instructionConfig[layer.args.instruction].label;
            }

            const args = _.clone(layer.args);
            delete args.instruction;
            const argList = _.map(args, (value, key) => {
                if (typeof value === 'undefined') {
                    value = '~';
                }
                else if (_.isArray(value)) {
                    value = value.join(', ');
                } else if (_.isObject(value)) {
                    value = '[[Expand for more details]]';
                }

                return `<strong>${key}</strong>: ${value}`;
            });

            if (argList.length) {
                return `<strong>${label}</strong>, ${argList.join(' | ')}`;
            }

            return `<strong>${label}</strong>`;
        },
        defaultArgs() {
            return {
                instruction: null
            }
        }
    },
    'php-extension': {
        label: 'PHP Extensions',
        component: PhpExtensionLayer,
        teaser(layer) {
            return (layer.args.extensions || []).join(', ');
        },
        defaultArgs() {
            return {
                extensions: []
            }
        }
    }
}

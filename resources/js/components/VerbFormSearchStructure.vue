<template>
  <div class="border border-gray-300 max-w-sm lg:max-w-full mb-4">
    <p class="uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2">
      Structure
    </p>

    <div class="lg:flex">
      <div class="lg:flex lg:border-r border-gray-400">
        <search-field
          label="Class"
          for-id="class-select"
        >
          <select
            id="class-select"
            :name="prefix + '[classes][]'"
            class="form-select rounded-none border-none w-full"
          >
            <option
              v-for="verbClass in classes"
              :key="verbClass.abv"
            >
              {{ verbClass.abv }}
            </option>
          </select>
        </search-field>
      </div>

      <div class="lg:flex lg:border-r border-gray-400">
        <search-field
          label="Subject"
          for-id="subject-select"
        >
          <select
            id="subject-select"
            class="form-select rounded-none border-none w-full"
            @change="updateSubject"
          >
            <option
              v-for="feature in features"
              :key="feature.name"
            >
              {{ feature.name }}
            </option>
          </select>

          <input
            v-if="subject && subject.person"
            v-model="subject.person"
            data-testid="subject-person"
            :name="prefix + '[subject_persons][]'"
            type="hidden"
          />

          <input
            v-if="subject && subject.number"
            v-model="subject.number"
            data-testid="subject-number"
            :name="prefix + '[subject_numbers][]'"
            type="hidden"
          />

          <input
            v-if="subject && subject.obviative_code"
            v-model="subject.obviative_code"
            data-testid="subject-obviative-code"
            :name="prefix + '[subject_obviative_code][]'"
            type="hidden"
          />
        </search-field>

        <search-field
          label="Primary Object"
          for-id="primary-object-select"
        >
          <select
            id="primary-object-select"
            class="form-select rounded-none border-none w-full"
            @change="updatePrimaryObject"
          >
            <option>
              None
            </option>
            <option
              v-for="feature in features"
              :key="feature.name"
            >
              {{ feature.name }}
            </option>
          </select>

          <input
            v-if="primaryObject === null"
            data-testid="primary-object-disabled"
            type="hidden"
            :name="prefix + '[primary_object]'"
            value="0"
          />

          <input
            v-if="primaryObject && primaryObject.person"
            v-model="primaryObject.person"
            data-testid="primary-object-person"
            :name="prefix + '[primary_object_persons][]'"
            type="hidden"
          />

          <input
            v-if="primaryObject && primaryObject.number"
            v-model="primaryObject.number"
            data-testid="primary-object-number"
            :name="prefix + '[primary_object_numbers][]'"
            type="hidden"
          />

          <input
            v-if="primaryObject && primaryObject.obviative_code"
            v-model="primaryObject.obviative_code"
            data-testid="primary-object-obviative-code"
            :name="prefix + '[primary_object_obviative_code][]'"
            type="hidden"
          />
        </search-field>

        <search-field
          label="Secondary object"
          for-id="secondary-object-select"
        >
          <select
            id="secondary-object-select"
            class="form-select rounded-none border-none w-full"
            @change="updateSecondaryObject"
          >
            <option>
              None
            </option>
            <option
              v-for="feature in features"
              :key="feature.name"
            >
              {{ feature.name }}
            </option>
          </select>

          <input
            v-if="secondaryObject === null"
            data-testid="secondary-object-disabled"
            type="hidden"
            :name="prefix + '[secondary_object]'"
            value="0"
          />

          <input
            v-if="secondaryObject && secondaryObject.person"
            v-model="secondaryObject.person"
            data-testid="secondary-object-person"
            :name="prefix + '[secondary_object_persons][]'"
            type="hidden"
          />

          <input
            v-if="secondaryObject && secondaryObject.number"
            v-model="secondaryObject.number"
            data-testid="secondary-object-number"
            :name="prefix + '[secondary_object_numbers][]'"
            type="hidden"
          />

          <input
            v-if="secondaryObject && secondaryObject.obviative_code"
            v-model="secondaryObject.obviative_code"
            data-testid="secondary-object-obviative-code"
            :name="prefix + '[secondary_object_obviative_code][]'"
            type="hidden"
          />
        </search-field>
      </div>

      <div class="lg:flex lg:border-r border-gray-400">
        <search-field
          label="Order"
          for-id="order-select"
        >
          <select
            id="order-select"
            :name="prefix + '[orders][]'"
            class="form-select rounded-none border-none w-full"
          >
            <option
              v-for="order in orders"
              :key="order.name"
            >
              {{ order.name }}
            </option>
          </select>
        </search-field>

        <search-field
          label="Mode"
          for-id="mode-select"
        >
          <select
            id="mode-select"
            :name="prefix + '[modes][]'"
            class="form-select rounded-none border-none w-full"
          >
            <option
              v-for="mode in modes"
              :key="mode.name"
            >
              {{ mode.name }}
            </option>
          </select>
        </search-field>
      </div>

      <div class="lg:flex">
        <search-field
          label="Negative"
          for-id="negative-checkbox"
        >
          <div
            class="bg-white flex justify-center w-full"
          >
            <input
              id="negative-checkbox"
              type="checkbox"
              :name="prefix + '[negative]'"
              class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
            />
          </div>
        </search-field>

        <search-field
          label="Diminutive"
          for-id="diminutive-checkbox"
        >
          <div
            class="bg-white flex justify-center w-full"
          >
            <input
              id="diminutive-checkbox"
              type="checkbox"
              :name="prefix + '[diminutive]'"
              class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
            />
          </div>
        </search-field>
      </div>
    </div>
  </div>
</template>

<script>
import VerbFormSearchStructureField from './VerbFormSearchStructureField';

export default {
  components: {
    'search-field': VerbFormSearchStructureField
  },

  props: {
    prefix: {
      type: String,
      default: ''
    },

    classes: {
      type: Array,
      required: true
    },

    orders: {
      type: Array,
      required: true
    },

    modes: {
      type: Array,
      required: true
    },

    features: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      subject: null,
      primaryObject: null,
      secondaryObject: null
    };
  },

  created() {
    const subject = this.features[0];
    this.subject = subject;
  },

  methods: {
    updateSubject(evt) {
      this.updateFeature(evt, 'subject');
    },

    updatePrimaryObject(evt) {
      this.updateFeature(evt, 'primaryObject');
    },

    updateSecondaryObject(evt) {
      this.updateFeature(evt, 'secondaryObject');
    },

    updateFeature(evt, featureName) {
      const name = evt.target.value;
      const feature = this.features.find(f => f.name === name);

      if (feature) {
        this[featureName] = feature;
      } else {
        this[featureName] = null;
      }
    }
  }
};
</script>

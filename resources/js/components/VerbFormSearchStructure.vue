<template>
  <div class="flex p-3 mb-6 bg-gray-200">
    <div>
      <label
        for="class-select"
        class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
      >
        Class
      </label>
      <select
        id="class-select"
        :name="prefix + '[classes][]'"
        class="form-select rounded-none border-none"
      >
        <option
          v-for="verbClass in classes"
          :key="verbClass.abv"
        >
          {{ verbClass.abv }}
        </option>
      </select>
    </div>

    <div class="mx-3 flex">
      <div class="flex flex-col">
        <label
          for="subject-select"
          class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
        >
          Subject
        </label>
        <select
          id="subject-select"
          class="form-select rounded-none border-none"
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
      </div>

      <div class="flex flex-col">
        <label
          for="primary-object-select"
          class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
        >
          Primary object
        </label>
        <select
          id="primary-object-select"
          class="form-select rounded-none border-none"
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
      </div>

      <div class="flex flex-col">
        <label
          for="secondary-object-select"
          class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
        >
          Secondary object
        </label>
        <select
          id="secondary-object-select"
          class="form-select rounded-none border-none"
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
      </div>
    </div>

    <div class="flex">
      <div>
        <label
          for="order-select"
          class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
        >
          Order
        </label>
        <select
          id="order-select"
          :name="prefix + '[orders][]'"
          class="form-select rounded-none border-none"
        >
          <option
            v-for="order in orders"
            :key="order.name"
          >
            {{ order.name }}
          </option>
        </select>
      </div>

      <div class="mr-3">
        <label
          for="mode-select"
          class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2"
        >
          Mode
        </label>
        <select
          id="mode-select"
          :name="prefix + '[modes][]'"
          class="form-select rounded-none border-none"
        >
          <option
            v-for="mode in modes"
            :key="mode.name"
          >
            {{ mode.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="flex">
      <label>
        <span class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2">
          Negative
        </span>
        <div class="bg-white flex justify-center">
          <input
            type="checkbox"
            :name="prefix + '[negative]'"
            class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
          />
        </div>
      </label>

      <label>
        <span class="block uppercase text-xs font-semibold bg-gray-700 text-gray-200 p-2">
          Diminutive
        </span>
        <div class="bg-white flex justify-center">
          <input
            type="checkbox"
            :name="prefix + '[diminutive]'"
            class="form-checkbox h-6 w-6 my-2 rounded-none text-blue-400"
          />
        </div>
      </label>
    </div>
  </div>
</template>

<script>
export default {
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
